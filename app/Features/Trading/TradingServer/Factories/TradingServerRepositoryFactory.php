<?php

namespace App\Features\Trading\TradingServer\Factories;

use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\TradingServerRepository;

class TradingServerRepositoryFactory
{
    public function make(): TradingServerRepositoryInterface
    {
        return new TradingServerRepository();
    }
}