<?php

namespace App\Features\TradingServer\Repositories\Contracts;

use App\Features\TradingServer\Models\Security;
use App\Features\TradingServer\Models\Symbol;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SecurityRepositoryInterface
{
    public function create(string $name, string $tradingServerId): Security;

    public function updateOrCreateForTradingServer(string $name, string $tradingServerId): Security;

    /**
     * Crea o actualiza filas en `symbols` y sincroniza el pivot security–symbol para esta security.
     *
     * @param  array<int, array{name: string, alpha: string, stype: int, trading_server_id: string}>  $symbols
     * @return Collection<int, Symbol>
     */
    public function addSymbols(Security $security, array $symbols): Collection;

    public function deleteSecuritiesByIds(array $securityIds): void;

    public function deleteAllByTradingServerId(string $tradingServerId): void;

    /**
     * @param  array{trading_server_id: string, name?: string|null, server_group_id?: string|null}  $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;
}
