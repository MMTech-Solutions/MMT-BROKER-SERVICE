<?php

namespace App\Features\Manager\Repositories\Contracts;

use App\Features\Manager\Models\Manager;
use Illuminate\Pagination\LengthAwarePaginator;

interface ManagerRepositoryInterface
{
    public function findById(string $id): ?Manager;

    public function deleteById(string $id): void;

    public function paginate(array $filters, int $perPage): LengthAwarePaginator;

    public function create(array $attributes): Manager;

    public function update(Manager $manager, array $attributes): Manager;
}