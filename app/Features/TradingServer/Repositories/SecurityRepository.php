<?php

namespace App\Features\TradingServer\Repositories;

use App\Features\TradingServer\Models\Security;
use App\Features\TradingServer\Models\Symbol;
use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SecurityRepository implements SecurityRepositoryInterface
{
    public function create(string $name, string $tradingServerId): Security
    {
        return Security::create([
            'name' => $name,
            'trading_server_id' => $tradingServerId,
        ]);
    }

    public function updateOrCreateForTradingServer(string $name, string $tradingServerId): Security
    {
        return Security::updateOrCreate(
            [
                'name' => $name,
                'trading_server_id' => $tradingServerId,
            ],
            [],
        );
    }

    public function addSymbols(Security $security, array $symbols): Collection
    {
        $resolved = collect();

        foreach ($symbols as $attributes) {
            $resolved->push(Symbol::updateOrCreate(
                [
                    'alpha' => $attributes['alpha'],
                    'trading_server_id' => $attributes['trading_server_id'],
                ],
                [
                    'name' => $attributes['name'],
                    'stype' => $attributes['stype'],
                ],
            ));
        }

        $security->symbols()->sync(
            $resolved->pluck('id')->unique()->values()->all(),
        );

        return $resolved;
    }

    public function deleteSecuritiesByIds(array $securityIds): void
    {
        Security::whereIn('id', $securityIds)->delete();
    }

    public function deleteAllByTradingServerId(string $tradingServerId): void
    {
        Security::where('trading_server_id', $tradingServerId)->delete();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Security::query()
            ->where('trading_server_id', $filters['trading_server_id'])
            ->when(($filters['server_group_id'] ?? null) !== null, function ($query) use ($filters) {
                $query->whereHas('serverGroups', fn ($q) => $q->where('server_groups.id', $filters['server_group_id']));
            })
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->orderBy('position')
            ->orderBy('created_at')
            ->paginate($perPage);
    }
}
