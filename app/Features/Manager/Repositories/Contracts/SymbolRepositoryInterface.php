<?php

namespace App\Features\Manager\Repositories\Contracts;

use App\Features\Manager\Models\Symbol;

interface SymbolRepositoryInterface
{
    public function create(string $name, string $alpha, int $stype, string $platformSettingId) : Symbol;

    public function deleteAllByPlatformSettingId(string $platformSettingId) : void;

    /**
     * @param string[] $symbolIds
     */
    public function deleteSymbolsByIds(array $symbolIds) : void;
}