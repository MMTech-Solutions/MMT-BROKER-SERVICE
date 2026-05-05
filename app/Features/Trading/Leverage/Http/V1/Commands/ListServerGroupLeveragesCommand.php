<?php

namespace App\Features\Trading\Leverage\Http\V1\Commands;

use App\Features\Trading\Leverage\Http\V1\Requests\ListServerGroupLeveragesRequest;

class ListServerGroupLeveragesCommand
{
    public function __construct(
        public readonly string $serverGroupId,
        public readonly ?string $name,
        public readonly ?int $value,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListServerGroupLeveragesRequest $request): self
    {
        /** @var array{name?: string, value?: int, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            serverGroupId: (string) $request->route('serverGroupUuid'),
            name: $validated['name'] ?? null,
            value: isset($validated['value']) ? (int) $validated['value'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
