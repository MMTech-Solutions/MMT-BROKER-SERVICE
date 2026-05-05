<?php

namespace App\Features\Trading\TradingServer\Repositories\Contracts;

use App\Features\Trading\TradingServer\Models\TradingServer;
use Illuminate\Pagination\LengthAwarePaginator;

interface TradingServerRepositoryInterface
{
    public function findById(string $id): ?TradingServer;

    public function deleteById(string $id): void;

    public function paginate(array $filters, int $perPage): LengthAwarePaginator;

    public function create(array $attributes): TradingServer;

    public function update(TradingServer $TradingServer, array $attributes): TradingServer;
}