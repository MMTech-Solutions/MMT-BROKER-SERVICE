<?php

namespace App\Features\Leverage\Http\V1\Commands;

use App\Features\Leverage\Http\V1\Requests\StoreLeverageRequest;

class StoreLeverageCommand
{
    public function __construct(
        public readonly string $name,
        public readonly int $value,
    ) {}

    public static function fromRequest(StoreLeverageRequest $request): self
    {
        /** @var array{name: string, value: int} $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            value: (int) $validated['value'],
        );
    }
}
