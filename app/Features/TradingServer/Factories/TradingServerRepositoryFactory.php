<?php

namespace App\Features\TradingServer\Factories;

use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\TradingServer\Repositories\TradingServerRepository;

class TradingServerRepositoryFactory
{
    public function make(): TradingServerRepositoryInterface
    {
        return new TradingServerRepository();
    }
}