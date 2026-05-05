<?php

namespace App\Features\Trading\Leverage\Repositories;

use App\Features\Trading\Leverage\Models\Leverage;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class LeverageRepository implements LeverageRepositoryInterface
{
    public function findById(string $id): ?Leverage
    {
        return Leverage::find($id);
    }

    public function deleteById(string $id): void
    {
        Leverage::where('id', $id)->delete();
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return Leverage::query()
            ->when(($filters['name'] ?? null) !== null, fn ($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(($filters['value'] ?? null) !== null, fn ($query) => $query->where('value', (int) $filters['value']))
            ->orderBy('value')
            ->paginate($perPage);
    }

    public function create(array $attributes): Leverage
    {
        return Leverage::create($attributes);
    }

    public function update(Leverage $leverage, array $attributes): Leverage
    {
        $leverage->fill($attributes);
        $leverage->save();

        return $leverage->refresh();
    }
}
