<?php

namespace App\Features\TradingServer\Repositories;

use App\Features\TradingServer\Exceptions\UniqueCredentialsException;
use App\Features\TradingServer\Models\TradingServer;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;

class TradingServerRepository implements TradingServerRepositoryInterface
{
    public function findById(string $id): ?TradingServer
    {
        return TradingServer::find($id);
    }

    public function deleteById(string $id): void
    {
        TradingServer::where('id', $id)->delete();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return TradingServer::query()
            ->when($filters['platform_id'] ?? null !== null, fn ($query) => $query->where('platform_id', $filters['platform_id']))
            ->when(($filters['host'] ?? null) !== null, fn ($query) => $query->where('host', 'like', '%'.$filters['host'].'%'))
            ->when(($filters['username'] ?? null) !== null, fn ($query) => $query->where('username', 'like', '%'.$filters['username'].'%'))
            ->when(($filters['port'] ?? null) !== null, fn ($query) => $query->where('port', (int) $filters['port']))
            ->when(($filters['enviroment'] ?? null) !== null, fn ($query) => $query->where('enviroment', (int) $filters['enviroment']))
            ->when(array_key_exists('is_active', $filters) && $filters['is_active'] !== null, function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            })
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    public function create(array $attributes): TradingServer
    {
        try {
            return TradingServer::create($attributes);
        } catch (UniqueConstraintViolationException $e) {
            throw new UniqueCredentialsException;
        }
    }

    public function update(TradingServer $tradingServer, array $attributes): TradingServer
    {
        if (isset($attributes['is_active']) && $attributes['is_active'] === false) {
            $tradingServer->serverGroups()->update([
                'is_active' => false,
            ]);
        }

        $tradingServer->fill($attributes);
        $tradingServer->save();

        return $tradingServer->refresh();
    }
}
