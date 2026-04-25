<?php

namespace App\Features\Manager\Repositories\Contracts;

use App\Features\Manager\Models\Symbol;
use Illuminate\Pagination\LengthAwarePaginator;

interface SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $managerId) : Symbol;

    public function deleteAllByManagerId(string $managerId) : void;

    /**
     * @param string[] $symbolIds
     */
    public function deleteSymbolsByIds(array $symbolIds) : void;

    /**
     * @param array{manager_id: string, name?: string|null, alpha?: string|null, stype?: int|null, security_id?: string|null} $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;
}