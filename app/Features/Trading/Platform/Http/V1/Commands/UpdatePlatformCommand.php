<?php

namespace App\Features\Trading\Platform\Http\V1\Commands;

use App\Features\Trading\Platform\Http\V1\Requests\UpdatePlatformRequest;

class UpdatePlatformCommand
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $platformId,
        public readonly array $attributes,
    ) {}

    public static function fromRequest(UpdatePlatformRequest $request): self
    {
        return new self(
            platformId: (string) $request->route('platformUuid'),
            attributes: $request->validated(),
        );
    }
}
