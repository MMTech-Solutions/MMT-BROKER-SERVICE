<?php

namespace App\Features\Account\Repositories;

use App\Features\Account\Models\Account;
use App\Features\Account\Repositories\Contracts\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    public function createBasic(
        string $externalUserId,
        string $externalTraderId,
        string $password,
        string $investorPassword,
        string $serverGroupId,
        string $leverageId,
    ): Account
    {
        return Account::create([
            'external_user_id' => $externalUserId,
            'external_trader_id' => $externalTraderId,
            'password' => $password,
            'investor_password' => $investorPassword,
            'server_group_id' => $serverGroupId,
            'leverage_id' => $leverageId,
            'initial_deposit' => 0,
            'current_balance' => 0,
            'current_equity' => 0,
            'current_credit' => 0,
            'margin' => 0,
            'free_margin' => 0,
            'is_active' => true,
            'is_trading_enabled' => true,
            'comments' => null,
        ]);
    }

    public function countByUserIdAndServerGroupId(string $externalUserId, string $serverGroupId): int
    {
        return Account::where([
            'external_user_id' => $externalUserId,
            'server_group_id' => $serverGroupId
        ])->count();
    }
}