<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Models\Symbol;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SymbolRepository implements SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $managerId) : Symbol
    {
        return Symbol::create([
            'name' => $name,
            'alpha' => $alpha,
            'stype' => $stype,
            'manager_id' => $managerId,
        ]);
    }

    public function deleteAllByManagerId(string $managerId) : void
    {
        Symbol::where('manager_id', $managerId)->delete();
    }

    public function deleteSymbolsByIds(array $symbolIds) : void
    {
        Symbol::whereIn('id', $symbolIds)->delete();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Symbol::query()
            ->where('manager_id', $filters['manager_id'])
            ->when(($filters['security_id'] ?? null) !== null, function ($query) use ($filters) {
                $query->whereHas('securities', fn ($q) => $q->where('securities.id', $filters['security_id']));
            })
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%'.$filters['name'].'%'))
            ->when(($filters['alpha'] ?? null) !== null, fn ($query) => $query->where('alpha', 'like', '%'.$filters['alpha'].'%'))
            ->when(array_key_exists('stype', $filters) && $filters['stype'] !== null, fn ($query) => $query->where('stype', (int) $filters['stype']))
            ->orderBy('created_at')
            ->paginate($perPage);
    }
}