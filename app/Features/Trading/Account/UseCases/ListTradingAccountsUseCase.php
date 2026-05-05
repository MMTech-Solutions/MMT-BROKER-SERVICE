<?php

namespace App\Features\Trading\Account\UseCases;

use App\Features\Trading\Account\Actions\ResolveEffectiveExternalUserIdForAccountListAction;
use App\Features\Trading\Account\DTOs\AccountDTO;
use App\Features\Trading\Account\Factories\AccountRepositoryFactory;
use App\Features\Trading\Account\Http\V1\Commands\ListTradingAccountsCommand;
use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\SharedFeatures\User\UserContext;
use Spatie\LaravelData\PaginatedDataCollection;

class ListTradingAccountsUseCase
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        private readonly UserContext $userContext,
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
        private readonly ResolveEffectiveExternalUserIdForAccountListAction $resolveEffectiveExternalUserIdForAccountListAction,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
    }

    public function execute(ListTradingAccountsCommand $command): PaginatedDataCollection
    {
        $effectiveExternalUserId = $this->resolveEffectiveExternalUserIdForAccountListAction->execute(
            requestedExternalUserId: $command->externalUserId,
            canReadAll: $this->userContext->can('account.read-all'),
            consumerId: $this->userContext->userInfo()->id,
        );

        $filters = [
            'external_user_id' => $effectiveExternalUserId,
            'external_trader_id' => $command->externalTraderId,
            'server_group_id' => $command->serverGroupId,
            'is_active' => $command->isActive,
        ];

        $accountsPaginator = $this->accountRepository->paginateForList(
            filters: $filters,
            perPage: $command->perPage,
        );

        return AccountDTO::collect(
            $accountsPaginator,
            PaginatedDataCollection::class
        );
    }
}
