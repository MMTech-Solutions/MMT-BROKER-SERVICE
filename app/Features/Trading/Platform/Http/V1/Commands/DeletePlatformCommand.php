<?php

namespace App\Features\Trading\Platform\Http\V1\Commands;

use App\Features\Trading\Platform\Http\V1\Requests\DeletePlatformRequest;

class DeletePlatformCommand
{
    public function __construct(
        public readonly string $platformId,
    ) {}

    public static function fromRequest(DeletePlatformRequest $request): self
    {
        return new self(
            platformId: (string) $request->route('platformUuid'),
        );
    }
}
