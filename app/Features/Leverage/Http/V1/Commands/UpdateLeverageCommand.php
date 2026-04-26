<?php

namespace App\Features\Leverage\Http\V1\Commands;

use App\Features\Leverage\Http\V1\Requests\UpdateLeverageRequest;

class UpdateLeverageCommand
{
    public function __construct(
        public readonly string $leverageId,
        public readonly ?string $name,
        public readonly ?int $value,
    ) {}

    public static function fromRequest(UpdateLeverageRequest $request): self
    {
        /** @var array{name?: string, value?: int} $validated */
        $validated = $request->validated();

        return new self(
            leverageId: $request->route('leverageUuid'),
            name: $validated['name'] ?? null,
            value: isset($validated['value']) ? (int) $validated['value'] : null,
        );
    }
}
