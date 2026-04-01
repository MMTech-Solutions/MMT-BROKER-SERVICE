<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\ListPlatformsRequest;

class ListPlatformsCommand
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $customName,
        public readonly ?string $code,
        public readonly ?int $volumeFactor,
        public readonly ?bool $isActive,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListPlatformsRequest $request): self
    {
        /** @var array{name?: string, custom_name?: string, code?: string, volume_factor?: int, is_active?: bool, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'] ?? null,
            customName: $validated['custom_name'] ?? null,
            code: $validated['code'] ?? null,
            volumeFactor: array_key_exists('volume_factor', $validated) ? (int) $validated['volume_factor'] : null,
            isActive: array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
