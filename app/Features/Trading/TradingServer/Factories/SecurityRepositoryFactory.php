<?php

namespace App\Features\Trading\TradingServer\Factories;

use App\Features\Trading\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\SecurityRepository;

class SecurityRepositoryFactory
{
    public function make(): SecurityRepositoryInterface
    {
        return new SecurityRepository();
    }
}