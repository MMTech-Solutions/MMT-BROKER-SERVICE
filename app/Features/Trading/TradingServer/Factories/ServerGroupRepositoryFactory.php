<?php

namespace App\Features\Trading\TradingServer\Factories;

use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\ServerGroupRepository;

class ServerGroupRepositoryFactory
{
    public function make(): ServerGroupRepositoryInterface
    {
        return new ServerGroupRepository();
    }
}