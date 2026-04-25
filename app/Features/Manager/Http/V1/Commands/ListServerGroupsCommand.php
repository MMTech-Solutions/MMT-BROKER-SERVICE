<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ListServerGroupsRequest;

class ListServerGroupsCommand
{
    public function __construct(
        public readonly string $managerId,
        public readonly ?string $name,
        public readonly ?string $metaName,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListServerGroupsRequest $request): self
    {
        /** @var array{name?: string, meta_name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            managerId: (string) $request->route('managerUuid'),
            name: $validated['name'] ?? null,
            metaName: $validated['meta_name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
