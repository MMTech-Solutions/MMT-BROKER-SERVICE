<?php

namespace App\Features\Manager\Factories;

use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\Manager\Repositories\SymbolRepository;

class SymbolRepositoryFactory
{
    public function make(): SymbolRepositoryInterface
    {
        return new SymbolRepository();
    }
}