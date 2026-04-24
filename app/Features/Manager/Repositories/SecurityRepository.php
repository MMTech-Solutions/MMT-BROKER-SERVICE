<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Models\Security;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use Illuminate\Support\Collection;

class SecurityRepository implements SecurityRepositoryInterface
{
    public function create(string $name, string $managerId) : Security
    {
        return Security::create([
            'name' => $name,
            'manager_id' => $managerId,
        ]);
    }

    public function addSymbols(Security $security, array $symbols) : Collection
    {
        $insertedSymbols = $security->symbols()->createMany($symbols);

        return collect($insertedSymbols);
    }

    public function syncSymbols(Security $security, Collection $symbols) : void
    {
        $security->symbols()->sync($symbols->pluck('id'));
    }

    public function deleteSecuritiesByIds(array $securityIds) : void
    {
        Security::whereIn('id', $securityIds)->delete();
    }
}