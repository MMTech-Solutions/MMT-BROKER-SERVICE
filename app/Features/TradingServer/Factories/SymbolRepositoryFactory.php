<?php

namespace App\Features\TradingServer\Factories;

use App\Features\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\TradingServer\Repositories\SymbolRepository;

class SymbolRepositoryFactory
{
    public function make(): SymbolRepositoryInterface
    {
        return new SymbolRepository();
    }
}