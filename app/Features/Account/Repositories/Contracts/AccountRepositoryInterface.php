<?php

namespace App\Features\Account\Repositories\Contracts;

use App\Features\Account\Models\Account;

interface AccountRepositoryInterface
{
    public function createBasic(
        string $externalUserId,
        string $externalTraderId,
        string $password,
        string $investorPassword,
        string $serverGroupId,
        string $leverageId,
    ): Account;

    public function countByUserIdAndServerGroupId(string $externalUserId, string $serverGroupId): int;
}