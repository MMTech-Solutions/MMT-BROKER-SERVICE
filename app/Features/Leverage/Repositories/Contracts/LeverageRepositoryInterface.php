<?php

namespace App\Features\Leverage\Repositories\Contracts;

use App\Features\Leverage\Models\Leverage;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeverageRepositoryInterface
{
    public function findById(string $id): ?Leverage;

    public function deleteById(string $id): void;

    public function paginate(array $filters, int $perPage): LengthAwarePaginator;

    public function create(array $attributes): Leverage;

    public function update(Leverage $leverage, array $attributes): Leverage;
}
