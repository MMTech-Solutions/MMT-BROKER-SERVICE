<?php

namespace App\Features\Platform\Repositories\Contracts;

use App\Features\Platform\Models\PlatformSetting;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlatformSettingRepositoryInterface
{
    /**
     * @param  array{host?: string|null, username?: string|null, port?: int|null, enviroment?: int|null, is_active?: bool|null}  $filters
     */
    public function paginate(array $filters, int $perPage, string $platformId): LengthAwarePaginator;

    public function findById(string $id): ?PlatformSetting;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): PlatformSetting;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(PlatformSetting $setting, array $attributes): PlatformSetting;

    public function delete(PlatformSetting $setting): void;
}
