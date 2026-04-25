<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Models\ServerGroup;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ServerGroupRepository implements ServerGroupRepositoryInterface
{
    public function basicCreate(string $name, string $managerId) : ServerGroup
    {
        return ServerGroup::create([
            'name' => $name,
            'meta_name' => $name, // Inicialmente se usa el mismo nombre para la meta_name.
            'manager_id' => $managerId,
        ]);
    }

    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void
    {
        $serverGroup->securities()->sync($securities->pluck('id'));
    }

    public function deleteAllByManagerId(string $managerId) : void
    {
        ServerGroup::where('manager_id', $managerId)->delete();
    }

    public function findByUuid(string $uuid) : ?ServerGroup
    {
        return ServerGroup::where('uuid', $uuid)->first();
    }

    public function getDiff(string $managerId, array $groupNames) : \Illuminate\Database\Eloquent\Collection
    {
        return ServerGroup::with([
            'securities',
            'securities.symbols',
        ])->where('manager_id', $managerId)
        ->whereNotIn('name', $groupNames)->get();
    }

    public function deleteById(string $id) : void
    {
        ServerGroup::where('id', $id)->delete();
    }

    public function findByName(string $name, string $managerId) : ?ServerGroup
    {
        return ServerGroup::with(['securities', 'securities.symbols'])
            ->where('name', $name)
            ->where('manager_id', $managerId)
            ->first();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return ServerGroup::query()
            ->where('manager_id', $filters['manager_id'])
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->when(($filters['meta_name'] ?? null) !== null, fn ($query) => $query->where('meta_name', 'like', '%'.$filters['meta_name'].'%'))
            ->orderBy('created_at')
            ->paginate($perPage);
    }
}