<?php

namespace App\Features\Platform\Repositories;

use App\Features\Platform\Models\PlatformSetting;
use App\Features\Platform\Exceptions\UniqueCredentialsException;
use App\Features\Platform\Repositories\Contracts\PlatformSettingRepositoryInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;

class PlatformSettingRepository implements PlatformSettingRepositoryInterface
{
    public function paginate(array $filters, int $perPage, string $platformId): LengthAwarePaginator
    {
        return PlatformSetting::query()
            ->where('platform_id', $platformId)
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

    public function findById(string $id): ?PlatformSetting
    {
        return PlatformSetting::whereKey($id)->first();
    }

    public function create(array $attributes): PlatformSetting
    {
        try {
            return PlatformSetting::create($attributes);
        }
        catch(UniqueConstraintViolationException $e) {
            throw new UniqueCredentialsException();
        }
    }

    public function update(PlatformSetting $setting, array $attributes): PlatformSetting
    {
        $setting->fill($attributes);
        $setting->save();

        return $setting->refresh();
    }

    public function delete(PlatformSetting $setting): void
    {
        $setting->delete();
    }
}
