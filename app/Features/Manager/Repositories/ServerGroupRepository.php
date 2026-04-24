<?php

namespace App\Features\Manager\Repositories;

use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Models\ServerGroup;
use Illuminate\Support\Collection;

class ServerGroupRepository implements ServerGroupRepositoryInterface
{
    public function basicCreate(string $name, string $platformSettingId) : ServerGroup
    {
        return ServerGroup::create([
            'name' => $name,
            'meta_name' => $name, // Inicialmente se usa el mismo nombre para la meta_name.
            'platform_setting_id' => $platformSettingId,
        ]);
    }

    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void
    {
        $serverGroup->securities()->sync($securities->pluck('id'));
    }

    public function deleteAllByPlatformSettingId(string $platformSettingId) : void
    {
        ServerGroup::where('platform_setting_id', $platformSettingId)->delete();
    }

    public function findByUuid(string $uuid) : ?ServerGroup
    {
        return ServerGroup::where('uuid', $uuid)->first();
    }

    public function getDiff(array $groupNames) : \Illuminate\Database\Eloquent\Collection
    {
        return ServerGroup::with([
            'securities',
            'securities.symbols',
        ])->whereNotIn('name', $groupNames)->get();
    }
}