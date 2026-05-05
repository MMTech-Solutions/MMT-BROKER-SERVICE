<?php

namespace App\Features\Trading\Account\UseCases;

use App\Features\Trading\Account\Actions\CheckAccountLimitsAction;
use App\Features\Trading\Account\Actions\CreateAccountAction;
use App\Features\Trading\Account\Events\TradingAccountCreatedEvent;
use App\Features\Trading\Account\Exceptions\InvalidAccountOperationException;
use App\Features\Trading\Account\Factories\AccountRepositoryFactory;
use App\Features\Trading\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Trading\Account\Models\Account;
use App\Features\Trading\Account\QueryObjects\SetInitialBalanceQueryObject;
use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\Trading\Leverage\Actions\FindLeverageForServerGroupAction;
use App\Features\Trading\TradingServer\DTOs\InitialAmountDTO;
use App\Features\Trading\TradingServer\DTOs\ServerGroupDTO;
use App\Features\Trading\TradingServer\Enums\EnvironmentEnum;
use App\Features\Trading\TradingServer\Exceptions\InitialAmountNotFoundException;
use App\Features\Trading\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\Trading\TradingServer\Factories\InitialAmountRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
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

    private ServerGroupRepositoryInterface $serverGroupRepository;

    private InitialAmountRepositoryInterface $initialAmountRepository;

    public function __construct(
        private readonly UserContext $userContext,
        private readonly FindLeverageForServerGroupAction $findLeverageForServerGroupAction,
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly InitialAmountRepositoryFactory $initialAmountRepositoryFactory,
        private readonly CheckAccountLimitsAction $checkAccountLimitsAction,
        private readonly CreateAccountAction $createAccountAction,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
        $this->user = $userContext->userInfo();
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
        $this->initialAmountRepository = $initialAmountRepositoryFactory->make();
    }

    public function execute(CreateAccountCommand $command)
    {
        $serverGroup = $this->findServerGroupDto($command->serverGroupId);

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

                $initialAmountDTO = $this->findInitialAmountDto($command->amountId);
                $initialAmount = $initialAmountDTO->amount;
                $balanceType = TransactionTypeEnum::BALANCE;
            }
        }

        $leverage = $this->findLeverageForServerGroupAction->execute(
            $command->serverGroupId,
            $command->leverageId
        );

        $this->checkAccountLimitsAction->execute(
            $serverGroup->accountLimits,
            $command->serverGroupId,
            $this->user->id
        );

        // TODO Aqui iran mas validaciones, permisos, ib, etc

        // ! Incluso en la logica de creacion de cuentas anterior a esta
        // ! no se estaba registrando el balance inicial ni en transacciones
        // ! ni en la cartera del usuario. EL dinero pasa directamente a la cuenta
        // ! de trading.

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

    private function findServerGroupDto(string $serverGroupId): ServerGroupDTO
    {
        $group = $this->serverGroupRepository->findByUuid($serverGroupId) ?? throw new ServerGroupNotFoundException;

        return ServerGroupDTO::from($group);
    }

    private function findInitialAmountDto(string $id): InitialAmountDTO
    {
        $initialAmount = $this->initialAmountRepository->findById($id) ?? throw new InitialAmountNotFoundException;

        return InitialAmountDTO::from($initialAmount);
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
