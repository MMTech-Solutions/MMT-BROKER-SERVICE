<?php

namespace App\Features\Manager\Factories;

use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Manager\Repositories\SecurityRepository;

class SecurityRepositoryFactory
{
    public function make(): SecurityRepositoryInterface
    {
        return new SecurityRepository();
    }
}