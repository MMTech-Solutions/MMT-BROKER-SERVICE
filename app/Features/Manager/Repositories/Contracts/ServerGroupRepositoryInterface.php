<?php

namespace App\Features\Manager\Repositories\Contracts;

use App\Features\Manager\Models\ServerGroup;
use App\Features\Manager\Models\Security;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;

interface ServerGroupRepositoryInterface
{
    public function findByUuid(string $uuid) : ?ServerGroup;

    /**
     * @throws UniqueConstraintViolationException
     */
    public function basicCreate(string $name, string $platformSettingId) : ServerGroup;

    /**
     * @param ServerGroup $serverGroup
     * @param Collection<Security> $securities
     */
    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void;

    public function deleteAllByPlatformSettingId(string $platformSettingId) : void;

    /**
     * Al pasar los nombres de los grupos retorna los que no estan presentes en la base de datos
     * @param array $groupNames
     */
    public function getDiff(array $groupNames) : \Illuminate\Database\Eloquent\Collection;
}