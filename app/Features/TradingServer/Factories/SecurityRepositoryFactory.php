<?php

namespace App\Features\TradingServer\Factories;

use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\TradingServer\Repositories\SecurityRepository;

class SecurityRepositoryFactory
{
    public function make(): SecurityRepositoryInterface
    {
        return new SecurityRepository();
    }
}