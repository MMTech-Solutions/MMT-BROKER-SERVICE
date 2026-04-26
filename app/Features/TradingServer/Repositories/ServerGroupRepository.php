<?php

namespace App\Features\TradingServer\Repositories;

use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Models\ServerGroup;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ServerGroupRepository implements ServerGroupRepositoryInterface
{
    public function basicCreate(string $name, string $TradingServerId) : ServerGroup
    {
        return ServerGroup::create([
            'name' => $name,
            'meta_name' => $name, // Inicialmente se usa el mismo nombre para la meta_name.
            'trading_server_id' => $TradingServerId,
        ]);
    }

    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void
    {
        $serverGroup->securities()->sync($securities->pluck('id'));
    }

    public function deleteAllByTradingServerId(string $TradingServerId) : void
    {
        ServerGroup::where('trading_server_id', $TradingServerId)->delete();
    }

    public function findByUuid(string $uuid) : ?ServerGroup
    {
        return ServerGroup::where('id', $uuid)->first();
    }

    public function getDiff(string $TradingServerId, array $groupNames) : \Illuminate\Database\Eloquent\Collection
    {
        return ServerGroup::with([
            'securities',
            'securities.symbols',
        ])->where('trading_server_id', $TradingServerId)
        ->whereNotIn('name', $groupNames)->get();
    }

    public function deleteById(string $id) : void
    {
        ServerGroup::where('id', $id)->delete();
    }

    public function findByName(string $name, string $TradingServerId) : ?ServerGroup
    {
        return ServerGroup::with(['securities', 'securities.symbols'])
            ->where('name', $name)
            ->where('trading_server_id', $TradingServerId)
            ->first();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return ServerGroup::query()
            ->where('trading_server_id', $filters['trading_server_id'])
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->when(($filters['meta_name'] ?? null) !== null, fn ($query) => $query->where('meta_name', 'like', '%'.$filters['meta_name'].'%'))
            ->orderBy('created_at')
            ->paginate($perPage);
    }
}