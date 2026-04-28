<?php

namespace App\Features\Account\UseCases;

use App\Features\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\Account\Factories\AccountRepositoryFactory;
use App\Features\TradingServer\Services\FindLeverageForServerGroupService;
use App\Features\TradingServer\Services\FindServerGroupByIdService;
use App\Features\Leverage\Services\FindLeverageByIdService;
use App\SharedFeatures\DTOs\User;
use App\SharedFeatures\TradingService\Exceptions\TradingServiceException;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Enums\LanguagesEnum;
use Mmt\TradingServiceSdk\Platforms\BrokerPasswordGenerator;
use Mmt\TradingServiceSdk\Platforms\MT5\Commands\CreateUserCommand;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\UserItem;
use App\SharedFeatures\Application\UserContext;
use App\Features\Account\Events\TradingAccountCreatedEvent;
use App\Features\Account\Services\CheckAccountLimitsService;


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
        private readonly FindLeverageByIdService $findLeverageByIdService,
        private readonly CheckAccountLimitsService $checkAccountLimitsService,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
        $this->user = $userContext->user();
    }

    public function execute(CreateAccountCommand $command)
    {
        $serverGroup = $this->findServerGroupByIdService->execute($command->serverGroupId);
        
        $leverage = $this->findLeverageForServerGroupService->execute(
            $command->serverGroupId,
            $command->leverageId
        );

        //TODO Aqui iran mas validaciones, permisos, ib, etc
        $this->checkAccountLimitsService->execute(
            $command->serverGroupId,
            $this->user->id
        );

        $tradingServiceSession = $this->tradingServiceFactory->make(
            $serverGroup->tradingServer->platform->toEnum(),
            $serverGroup->tradingServer->connection_id
        );
        
        $randomPassword = BrokerPasswordGenerator::generateRandomPassword();

        $userItem = $this->createExternalUser(
            $tradingServiceSession,
            $serverGroup->name,
            $leverage->value,
            $randomPassword
        );

        $account = $this->accountRepository->createBasic(
            externalUserId: $this->user->id,
            externalTraderId: $userItem->login,
            password: $randomPassword,
            investorPassword: $randomPassword,
            serverGroupId: $command->serverGroupId,
            leverageId: $command->leverageId,
        );

        event(new TradingAccountCreatedEvent(
            id: $account->id,
            externalUserId: $this->user->id,
            externalTraderId: $userItem->login,
            password: $randomPassword,
            investorPassword: $randomPassword,
            name: $this->user->name,
            email: $this->user->email,
        ));

        return $account;
    }


    /**
     * Creates a new external user in the trading service
     * @return UserItem The created user
     * @throws TradingServiceException If the user creation fails
     */
    private function createExternalUser(
        MT5TradingServiceInterface $tradingServiceSession,
        string $serverGroupName,
        int $leverageValue,
        string $randomPassword
    ) : UserItem
    {
        $createUserResult = $tradingServiceSession->createUser(
            new CreateUserCommand(
                password: $randomPassword,
                password_investor: $randomPassword,
                group: $serverGroupName,
                email: $this->user->email,
                leverage: $leverageValue,
                agent_account: $this->user->iblinkId,
                first_name: $this->user->name,
                last_name: $this->user->lastname,
                //! -- Esto hay que resolverlo desde algun lugar
                company: 'Some Company',
                //! --
                language: LanguagesEnum::ES_ES,
                country: $this->user->countryIso,
                city: $this->user->city,
                state: $this->user->state,
                zip_code: $this->user->zipcode,
                address: $this->user->address,
                phone: $this->user->phone,
                comment: 'Some comment',
            )
        );

        if ($createUserResult->isFailure()) {
            throw new TradingServiceException($createUserResult->getMessage());
        }

        return $createUserResult->getData(UserItem::class);
    }
}
