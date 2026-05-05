<?php

namespace App\Features\Platform\QueryObjects;

use App\Features\Platform\Models\Platform;
use App\SharedFeatures\User\UserContext;

class ListPlatformsQuery
{
    public function __construct(
        private readonly UserContext $userContext,
    ) {}

    public function handle(array $filters, int $perPage)
    {
        $query = Platform::query()
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->when(($filters['custom_name'] ?? null) !== null, fn ($query) => $query->where('custom_name', 'like', '%'.$filters['custom_name'].'%'))
            ->when(($filters['volume_factor'] ?? null) !== null, fn ($query) => $query->where('volume_factor', (int) $filters['volume_factor']))
            ->orderBy('name');

        if($this->userContext->can('platform.edit')) {
            $query->when(array_key_exists('is_active', $filters) && $filters['is_active'] !== null, function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            });
        }
        else {
            $query->where('is_active', true);
        }

        return $query->paginate($perPage);
    }
}