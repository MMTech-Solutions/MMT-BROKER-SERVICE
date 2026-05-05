<?php

namespace App\Features\Trading\Platform\Repositories\Contracts;

use App\Features\Trading\Platform\Models\Platform;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlatformRepositoryInterface
{
    /**
     * @param  array{name?: string|null, custom_name?: string|null, code?: string|null, volume_factor?: int|null, is_active?: bool|null}  $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;

    public function findById(string $id): ?Platform;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Platform;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Platform $platform, array $attributes): Platform;

    public function deleteById(string $id): void;
}
