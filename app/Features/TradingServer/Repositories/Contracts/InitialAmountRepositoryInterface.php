<?php

namespace App\Features\TradingServer\Repositories\Contracts;

use App\Features\TradingServer\Models\InitialAmount;

interface InitialAmountRepositoryInterface
{
    public function findById(string $id): ?InitialAmount;
}
