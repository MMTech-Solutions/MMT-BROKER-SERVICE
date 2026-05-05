<?php

namespace App\Features\Trading\Leverage\Repositories\Contracts;

use App\Features\Trading\Leverage\Models\Leverage;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeverageRepositoryInterface
{
    public function findById(string $id): ?Leverage;

    public function deleteById(string $id): void;

    public function paginate(array $filters, int $perPage): LengthAwarePaginator;

    public function create(array $attributes): Leverage;

    public function update(Leverage $leverage, array $attributes): Leverage;
}
