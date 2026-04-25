<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Models\Security;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Security::query()
            ->where('manager_id', $filters['manager_id'])
            ->when(($filters['server_group_id'] ?? null) !== null, function ($query) use ($filters) {
                $query->whereHas('serverGroups', fn ($q) => $q->where('server_groups.id', $filters['server_group_id']));
            })
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->orderBy('position')
            ->orderBy('created_at')
            ->paginate($perPage);
    }
}