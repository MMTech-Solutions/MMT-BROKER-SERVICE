<?php

namespace App\Features\TradingServer\Repositories\Contracts;

use App\Features\TradingServer\Models\Symbol;
use Illuminate\Pagination\LengthAwarePaginator;

interface SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $TradingServerId) : Symbol;

    public function deleteAllByTradingServerId(string $TradingServerId) : void;

    /**
     * @param string[] $symbolIds
     */
    public function deleteSymbolsByIds(array $symbolIds) : void;

    /**
     * @param array{trading_server_id: string, name?: string|null, alpha?: string|null, stype?: int|null, security_id?: string|null} $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;
}