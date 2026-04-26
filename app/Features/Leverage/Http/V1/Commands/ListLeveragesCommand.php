<?php

namespace App\Features\Leverage\Http\V1\Commands;

use App\Features\Leverage\Http\V1\Requests\ListLeveragesRequest;

class ListLeveragesCommand
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?int $value,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListLeveragesRequest $request): self
    {
        /** @var array{name?: string, value?: int, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'] ?? null,
            value: isset($validated['value']) ? (int) $validated['value'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
