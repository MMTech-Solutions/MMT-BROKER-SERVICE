<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Models\Symbol;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;

class SymbolRepository implements SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $managerId) : Symbol
    {
        return Symbol::create([
            'name' => $name,
            'alpha' => $alpha,
            'stype' => $stype,
            'manager_id' => $managerId,
        ]);
    }

    public function deleteAllByManagerId(string $managerId) : void
    {
        Symbol::where('manager_id', $managerId)->delete();
    }

    public function deleteSymbolsByIds(array $symbolIds) : void
    {
        Symbol::whereIn('id', $symbolIds)->delete();
    }
}