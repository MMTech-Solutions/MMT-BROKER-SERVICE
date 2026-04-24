<?php

namespace App\Features\Manager\Factories;

use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Repositories\ServerGroupRepository;

class ServerGroupRepositoryFactory
{
    public function make(): ServerGroupRepositoryInterface
    {
        return new ServerGroupRepository();
    }
}