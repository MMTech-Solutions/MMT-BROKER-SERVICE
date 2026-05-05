<?php

namespace App\Features\Trading\Account\Actions;

use App\Features\Trading\Account\Factories\AccountRepositoryFactory;
use App\Features\Trading\Account\Models\Account;
use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\Trading\Leverage\DTOs\LeverageDTO;
use App\Features\Trading\TradingServer\DTOs\ServerGroupDTO;
use App\SharedFeatures\User\User;
use App\SharedFeatures\TradingService\Exceptions\TradingServiceException;
use Mmt\TradingServiceSdk\Enums\LanguagesEnum;
use Mmt\TradingServiceSdk\Platforms\BrokerPasswordGenerator;
use Mmt\TradingServiceSdk\Platforms\MT5\Commands\CreateUserCommand;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\UserItem;

class CreateAccountAction
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
    }

    public function execute(
        MT5TradingServiceInterface $tradingServiceSession,
        ServerGroupDTO $serverGroup,
        User $user,
        LeverageDTO $leverage,
    ): Account {
        $randomPassword = BrokerPasswordGenerator::generateRandomPassword();

        $userItem = $this->createExternalUser(
            $tradingServiceSession,
            $user,
            $serverGroup->name,
            $leverage->value,
            $randomPassword
        );

        return $this->accountRepository->createBasic(
            externalUserId: $user->id,
            externalTraderId: $userItem->login,
            password: $randomPassword,
            investorPassword: $randomPassword,
            serverGroupId: $serverGroup->id,
            leverageId: $leverage->id,
        );
    }

    /**
     * Creates a new external user in the trading service
     *
     * @return UserItem The created user
     *
     * @throws TradingServiceException If the user creation fails
     */
    private function createExternalUser(
        MT5TradingServiceInterface $tradingServiceSession,
        User $user,
        string $serverGroupName,
        int $leverageValue,
        string $randomPassword
    ): UserItem {
        $createUserResult = $tradingServiceSession->createUser(
            new CreateUserCommand(
                password: $randomPassword,
                password_investor: $randomPassword,
                group: $serverGroupName,
                email: $user->email,
                leverage: $leverageValue,
                // agent_account: $user->iblinkId,
                first_name: $user->firstName,
                last_name: $user->lastName,
                // ! -- Esto hay que resolverlo desde algun lugar
                company: 'Some Company',
                // ! --
                language: LanguagesEnum::ES_ES,
                // country: $user->countryIso,
                // city: $user->city,
                // state: $user->state,
                // zip_code: $user->zipcode,
                // address: $user->address,
                // phone: $user->phone,
                comment: 'Some comment',
            )
        );

        if ($createUserResult->isFailure()) {
            throw new TradingServiceException($createUserResult->getMessage());
        }

        return $createUserResult->getData(UserItem::class);
    }
}
