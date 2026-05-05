<?php

namespace App\Features\Trading\Account\Actions;

use App\Features\Trading\Account\Exceptions\AccountLimitExceededException;
use App\Features\Trading\Account\Factories\AccountRepositoryFactory;
use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;

class CheckAccountLimitsAction
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        private readonly AccountRepositoryFactory $accountRepositoryFactory,
    ) {
        $this->accountRepository = $accountRepositoryFactory->make();
    }

    /**
     * @throws AccountLimitExceededException
     */
    public function execute(int $accountLimits, string $serverGroupId, string $externalUserId): void
    {
        if ($accountLimits == 0) {
            return;
        }

        $accountCount = $this->accountRepository->countByUserIdAndServerGroupId(
            $externalUserId,
            $serverGroupId
        );

        if ($accountLimits <= $accountCount) {
            throw new AccountLimitExceededException;
        }
    }
}
