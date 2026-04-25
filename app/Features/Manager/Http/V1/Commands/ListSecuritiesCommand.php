<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ListSecuritiesRequest;

class ListSecuritiesCommand
{
    public function __construct(
        public readonly string $managerId,
        public readonly ?string $name,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListSecuritiesRequest $request): self
    {
        /** @var array{name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            managerId: (string) $request->route('managerUuid'),
            name: $validated['name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
