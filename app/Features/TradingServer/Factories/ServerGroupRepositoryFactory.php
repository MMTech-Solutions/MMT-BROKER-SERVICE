<?php

namespace App\Features\TradingServer\Factories;

use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Repositories\ServerGroupRepository;

class ServerGroupRepositoryFactory
{
    public function make(): ServerGroupRepositoryInterface
    {
        return new ServerGroupRepository();
    }
}