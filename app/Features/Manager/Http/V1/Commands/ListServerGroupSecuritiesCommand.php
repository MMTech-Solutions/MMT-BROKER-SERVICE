<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ListServerGroupSecuritiesRequest;

class ListServerGroupSecuritiesCommand
{
    public function __construct(
        public readonly string $managerId,
        public readonly string $serverGroupId,
        public readonly ?string $name,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListServerGroupSecuritiesRequest $request): self
    {
        /** @var array{name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            managerId: (string) $request->route('managerUuid'),
            serverGroupId: (string) $request->route('serverGroupUuid'),
            name: $validated['name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
