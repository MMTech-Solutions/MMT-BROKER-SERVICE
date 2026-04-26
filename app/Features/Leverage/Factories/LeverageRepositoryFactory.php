<?php

namespace App\Features\Leverage\Factories;

use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use App\Features\Leverage\Repositories\LeverageRepository;

class LeverageRepositoryFactory
{
    public function make(): LeverageRepositoryInterface
    {
        return new LeverageRepository();
    }
}
