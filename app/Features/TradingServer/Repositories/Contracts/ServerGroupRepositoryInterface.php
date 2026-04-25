<?php

namespace App\Features\TradingServer\Repositories\Contracts;

use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\Models\Security;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ServerGroupRepositoryInterface
{
    public function findByUuid(string $uuid) : ?ServerGroup;

    /**
     * @throws UniqueConstraintViolationException
     */
    public function basicCreate(string $name, string $TradingServerId) : ServerGroup;

    /**
     * @param ServerGroup $serverGroup
     * @param Collection<Security> $securities
     */
    public function syncSecurities(ServerGroup $serverGroup, Collection $securities) : void;

    public function deleteAllByTradingServerId(string $TradingServerId) : void;

    /**
     * Al pasar los nombres de los grupos retorna los que no estan presentes en la base de datos
     * @param array $groupNames
     */
    public function getDiff(string $TradingServerId, array $groupNames) : \Illuminate\Database\Eloquent\Collection;

    public function deleteById(string $id) : void;

    public function findByName(string $name, string $TradingServerId) : ?ServerGroup;

    /**
     * @param array{trading_server_id: string, name?: string|null, meta_name?: string|null} $filters
     */
    public function paginate(array $filters, int $perPage): LengthAwarePaginator;
}