<?php

namespace App\Features\TradingServer\Repositories\Contracts;

use App\Features\TradingServer\Models\Security;
use App\Features\TradingServer\Models\Symbol;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SecurityRepositoryInterface
{
    public function create(string $name, string $TradingServerId) : Security;

    /**
     * @param Security $security
     * @param array<{name: string, alpha: string, stype: int, trading_server_id: string}> $symbols
     */
    public function addSymbols(Security $security, array $symbols) : Collection;

    /**
     * @param Security $security
     * @param Collection<Symbol> $symbols
     * @return void
     */
    public function syncSymbols(Security $security, Collection $symbols) : void;

    public function deleteSecuritiesByIds(array $securityIds) : void;

    /**
     * @param array{trading_server_id: string, name?: string|null, server_group_id?: string|null} $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;
}