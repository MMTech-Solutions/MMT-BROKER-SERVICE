<?php

namespace App\Features\Trading\Account\Repositories;

use App\Features\Trading\Account\Models\Account;
use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function paginateForList(array $filters, int $perPage): LengthAwarePaginator
    {
        return Account::query()
            ->when(
                array_key_exists('external_user_id', $filters) && $filters['external_user_id'] !== null,
                fn ($query) => $query->where('external_user_id', $filters['external_user_id'])
            )
            ->when(
                array_key_exists('external_trader_id', $filters) && $filters['external_trader_id'] !== null,
                fn ($query) => $query->where('external_trader_id', $filters['external_trader_id'])
            )
            ->when(
                array_key_exists('server_group_id', $filters) && $filters['server_group_id'] !== null,
                fn ($query) => $query->where('server_group_id', $filters['server_group_id'])
            )
            ->when(
                array_key_exists('is_active', $filters) && $filters['is_active'] !== null,
                fn ($query) => $query->where('is_active', (bool) $filters['is_active'])
            )
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}