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
    public function basicCreate(string $name, string $managerId) : ServerGroup;

    /**
     * @param ServerGroup $serverGroup
     * @param Collection<Security> $securities
     */
    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void;

    public function deleteAllByManagerId(string $managerId) : void;

    /**
     * Al pasar los nombres de los grupos retorna los que no estan presentes en la base de datos
     * @param array $groupNames
     */
    public function getDiff(string $managerId, array $groupNames) : \Illuminate\Database\Eloquent\Collection;

    public function deleteById(string $id) : void;

    public function findByName(string $name, string $managerId) : ?ServerGroup;
}