<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ListSymbolsRequest;

class ListSymbolsCommand
{
    public function __construct(
        public readonly string $managerId,
        public readonly ?string $name,
        public readonly ?string $alpha,
        public readonly ?int $stype,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListSymbolsRequest $request): self
    {
        /** @var array{name?: string, alpha?: string, stype?: int, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            managerId: (string) $request->route('managerUuid'),
            name: $validated['name'] ?? null,
            alpha: $validated['alpha'] ?? null,
            stype: array_key_exists('stype', $validated) ? (int) $validated['stype'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
