<?php

namespace App\Features\Trading\TradingServer\Repositories\Contracts;

use App\Features\Trading\TradingServer\Models\InitialAmount;

interface InitialAmountRepositoryInterface
{
    public function findById(string $id): ?InitialAmount;
}
