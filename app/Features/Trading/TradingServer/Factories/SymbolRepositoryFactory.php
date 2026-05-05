<?php

namespace App\Features\Trading\TradingServer\Factories;

use App\Features\Trading\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\SymbolRepository;

class SymbolRepositoryFactory
{
    public function make(): SymbolRepositoryInterface
    {
        return new SymbolRepository();
    }
}