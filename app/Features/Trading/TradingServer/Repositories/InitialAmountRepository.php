<?php

namespace App\Features\Trading\TradingServer\Repositories;

use App\Features\Trading\TradingServer\Models\InitialAmount;
use App\Features\Trading\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;

class InitialAmountRepository implements InitialAmountRepositoryInterface
{
    public function findById(string $id): ?InitialAmount
    {
        return InitialAmount::find($id);
    }
}
