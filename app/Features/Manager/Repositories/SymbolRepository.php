<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Models\Symbol;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;

class SymbolRepository implements SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $platformSettingId) : Symbol
    {
        return Symbol::create([
            'name' => $name,
            'alpha' => $alpha,
            'stype' => $stype,
            'platform_setting_id' => $platformSettingId,
        ]);
    }

    public function deleteAllByPlatformSettingId(string $platformSettingId) : void
    {
        Symbol::where('platform_setting_id', $platformSettingId)->delete();
    }

    public function deleteSymbolsByIds(array $symbolIds) : void
    {
        Symbol::whereIn('id', $symbolIds)->delete();
    }
}