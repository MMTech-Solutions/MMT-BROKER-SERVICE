<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\StorePlatformRequest;

class StorePlatformCommand
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $customName,
        public readonly int $volumeFactor,
        public readonly bool $isActive,
    ) {}

    public static function fromRequest(StorePlatformRequest $request): self
    {
        /** @var array{name: string, volume_factor: int, custom_name?: string|null, is_active?: bool} $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            customName: $validated['custom_name'] ?? null,
            volumeFactor: (int) $validated['volume_factor'],
            isActive: (bool) ($validated['is_active'] ?? false),
        );
    }
}
