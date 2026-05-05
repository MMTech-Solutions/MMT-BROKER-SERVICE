<?php

namespace App\Features\Trading\Leverage\Factories;

use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use App\Features\Trading\Leverage\Repositories\LeverageRepository;

class LeverageRepositoryFactory
{
    public function make(): LeverageRepositoryInterface
    {
        return new LeverageRepository();
    }
}
