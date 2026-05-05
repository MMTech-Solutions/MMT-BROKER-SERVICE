<?php

namespace App\Features\TradingServer\Repositories;

use App\Features\TradingServer\Models\InitialAmount;
use App\Features\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;

class InitialAmountRepository implements InitialAmountRepositoryInterface
{
    public function findById(string $id): ?InitialAmount
    {
        return InitialAmount::find($id);
    }
}
