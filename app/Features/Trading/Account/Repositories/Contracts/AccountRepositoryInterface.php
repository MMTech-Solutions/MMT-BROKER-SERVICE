<?php

namespace App\Features\Trading\Account\Repositories\Contracts;

use App\Features\Trading\Account\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;

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

    /**
     * @param  array{
     *   external_user_id?: ?string,
     *   external_trader_id?: ?string,
     *   server_group_id?: ?string,
     *   is_active?: ?bool
     * }  $filters
     */
    public function paginateForList(array $filters, int $perPage): LengthAwarePaginator;
}