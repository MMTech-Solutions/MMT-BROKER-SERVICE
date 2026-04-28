<?php

namespace App\Features\Account\Services;

use App\Features\Account\Exceptions\AccountLimitExceededException;
use App\Features\TradingServer\Services\FindServerGroupByIdService;
use App\Features\Account\Factories\AccountRepositoryFactory;
use App\Features\Account\Repositories\Contracts\AccountRepositoryInterface;

class CheckAccountLimitsService
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        private readonly FindServerGroupByIdService $findServerGroupByIdService,
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
    }

    public function execute(string $serverGroupId, string $externalUserId): void
    {
        $serverGroup = $this->findServerGroupByIdService->execute($serverGroupId);

        $accountCount = $this->accountRepository->countByUserIdAndServerGroupId(
            $externalUserId,
            $serverGroupId
        );

        if ($serverGroup->account_limits > 0 && $serverGroup->account_limits <= $accountCount) {
            throw new AccountLimitExceededException();
        }
    }
}