<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Exceptions\UniqueCredentialsException;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Manager\Models\Manager;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepository implements ManagerRepositoryInterface
{
    public function findById(string $id): ?Manager
    {
        return Manager::find($id);
    }

    public function deleteById(string $id): void
    {
        Manager::where('id', $id)->delete();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Manager::query()
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

    public function create(array $attributes): Manager
    {
        try {
            return Manager::create($attributes);
        }
        catch(UniqueConstraintViolationException $e) {
            throw new UniqueCredentialsException();
        }
    }

    public function update(Manager $manager, array $attributes): Manager
    {
        $manager->fill($attributes);
        $manager->save();

        return $manager->refresh();
    }
}