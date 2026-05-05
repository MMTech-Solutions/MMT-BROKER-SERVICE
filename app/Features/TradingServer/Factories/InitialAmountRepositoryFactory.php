<?php

namespace App\Features\TradingServer\Factories;

use App\Features\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;
use App\Features\TradingServer\Repositories\InitialAmountRepository;

class InitialAmountRepositoryFactory
{
    public function make(): InitialAmountRepositoryInterface
    {
        return new InitialAmountRepository;
    }
}
