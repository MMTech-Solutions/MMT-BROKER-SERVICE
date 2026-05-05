<?php

namespace App\Features\Account\UseCases;

use App\Features\Account\Actions\CheckAccountLimitsAction;
use App\Features\Account\Actions\CreateAccountAction;
use App\Features\Account\Events\TradingAccountCreatedEvent;
use App\Features\Account\Exceptions\InvalidAccountOperationException;
use App\Features\Account\Factories\AccountRepositoryFactory;
use App\Features\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Account\Models\Account;
use App\Features\Account\QueryObjects\SetInitialBalanceQueryObject;
use App\Features\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\TradingServer\Enums\EnvironmentEnum;
use App\Features\TradingServer\Services\FindInitialAmountByIdService;
use App\Features\TradingServer\Services\FindLeverageForServerGroupService;
use App\Features\TradingServer\Services\FindServerGroupByIdService;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use App\SharedFeatures\User\User;
use App\SharedFeatures\User\UserContext;
use App\SharedFeatures\ValueObjects\Currency;
use App\SharedFeatures\ValueObjects\PositiveMoney;
use App\SharedFeatures\ValueObjects\PositiveNumber;
use Mmt\TradingServiceSdk\Platforms\MT5\Commands\TransactionCommand;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\Enums\TransactionTypeEnum;

class CreateAccountUseCase
{
    protected AccountRepositoryInterface $accountRepository;

    protected User $user;

    public function __construct(
        private readonly UserContext $userContext,
        private readonly FindLeverageForServerGroupService $findLeverageForServerGroupService,
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly FindServerGroupByIdService $findServerGroupByIdService,
        private readonly CheckAccountLimitsAction $checkAccountLimitsAction,
        private readonly CreateAccountAction $createAccountAction,
        private readonly FindInitialAmountByIdService $findInitialAmountByIdService,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
        $this->user = $userContext->userInfo();
    }

    public function execute(CreateAccountCommand $command)
    {
        $serverGroup = $this->findServerGroupByIdService->execute($command->serverGroupId);

        $initialAmount = PositiveNumber::zero();
        $balanceType = null;

        if ($serverGroup->defaultAmount->isGreaterThanZero()) {
            // Si el server_group tiene un monto inicial por defecto, se usa ese monto.
            $initialAmount = $serverGroup->defaultAmount;
            $balanceType = $serverGroup->defaultAmountType;
        } else {
            if ($serverGroup->environment == EnvironmentEnum::DEMO) {
                // Si el usuario no especifica un monto inicial y el server_group es DEMO, se lanza una excepcion.
                if ($command->amountId == null) {
                    throw new InvalidAccountOperationException;
                }

                $initialAmountDTO = $this->findInitialAmountByIdService->execute($command->amountId);
                $initialAmount = $initialAmountDTO->amount;
                $balanceType = TransactionTypeEnum::BALANCE;
            }
        }

        $leverage = $this->findLeverageForServerGroupService->execute(
            $command->serverGroupId,
            $command->leverageId
        );

        $this->checkAccountLimitsAction->execute(
            $serverGroup->accountLimits,
            $command->serverGroupId,
            $this->user->id
        );

        // TODO Aqui iran mas validaciones, permisos, ib, etc

        //! Incluso en la logica de creacion de cuentas anterior a esta
        //! no se estaba registrando el balance inicial ni en transacciones
        //! ni en la cartera del usuario. EL dinero pasa directamente a la cuenta
        //! de trading.

        $tradingServiceSession = $this->tradingServiceFactory->make(
            $serverGroup->platform,
            $serverGroup->connectionId
        );

        $account = $this->createAccountAction->execute(
            $tradingServiceSession,
            $serverGroup,
            $this->user,
            $leverage,
        );

        if ($initialAmount->isGreaterThanZero()) {
            $this->setInitialBalance(
                $tradingServiceSession,
                $initialAmount,
                $account,
                $balanceType,
            );
        }

        event(new TradingAccountCreatedEvent(
            id: $account->id,
            externalUserId: $this->user->id,
            externalTraderId: $account->external_trader_id,
            password: $account->password,
            investorPassword: $account->investor_password,
            userFullName: $this->user->fullName,
            userEmail: $this->user->email,
        ));

        return $account;
    }

    private function setInitialBalance(
        MT5TradingServiceInterface $tradingServiceSession,
        PositiveNumber $initialAmount,
        Account $account,
        TransactionTypeEnum $balanceType
    ): void {
        $money = new PositiveMoney(
            amount: $initialAmount,
            currency: Currency::fallback(),
        );

        $transactionCommand = new TransactionCommand(
            login: $account->external_trader_id,
            amount: $money->toFloat(),
            type: $balanceType,
        );

        $transactionResult = $tradingServiceSession->setBalance($transactionCommand);

        if ($transactionResult->isFailure()) {
            throw new InvalidAccountOperationException('Error setting initial balance');
        }

        SetInitialBalanceQueryObject::execute(
            accountId: $account->id,
            money: $money,
        );
    }
}
