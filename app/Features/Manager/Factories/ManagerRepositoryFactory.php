<?php

namespace App\Features\Manager\Factories;

use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Manager\Repositories\ManagerRepository;

class ManagerRepositoryFactory
{
    public function make(): ManagerRepositoryInterface
    {
        return new ManagerRepository();
    }
}