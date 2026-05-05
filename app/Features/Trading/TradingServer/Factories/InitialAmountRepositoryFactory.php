<?php

namespace App\Features\Trading\TradingServer\Factories;

use App\Features\Trading\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\InitialAmountRepository;

class InitialAmountRepositoryFactory
{
    public function make(): InitialAmountRepositoryInterface
    {
        return new InitialAmountRepository;
    }
}
