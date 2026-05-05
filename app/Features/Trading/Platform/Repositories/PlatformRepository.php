<?php

namespace App\Features\Trading\Platform\Repositories;

use App\Features\Trading\Platform\Models\Platform;
use App\Features\Trading\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PlatformRepository implements PlatformRepositoryInterface
{
    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Platform::query()
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->when(($filters['custom_name'] ?? null) !== null, fn ($query) => $query->where('custom_name', 'like', '%'.$filters['custom_name'].'%'))
            ->when(($filters['volume_factor'] ?? null) !== null, fn ($query) => $query->where('volume_factor', (int) $filters['volume_factor']))
            ->when(array_key_exists('is_active', $filters) && $filters['is_active'] !== null, function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(string $id): ?Platform
    {
        return Platform::query()->whereKey($id)->first();
    }

    public function create(array $attributes): Platform
    {
        return Platform::query()->create($attributes);
    }

    public function update(Platform $platform, array $attributes): Platform
    {
        $platform->fill($attributes);
        $platform->save();

        return $platform->refresh();
    }

    public function deleteById(string $id): void
    {
        Platform::where('id', $id)->delete();
    }
}
