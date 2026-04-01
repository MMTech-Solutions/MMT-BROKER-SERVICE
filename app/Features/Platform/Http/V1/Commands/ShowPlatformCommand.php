<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\ShowPlatformRequest;

class ShowPlatformCommand
{
    public function __construct(
        public readonly string $platformId,
    ) {}

    public static function fromRequest(ShowPlatformRequest $request): self
    {
        return new self(
            platformId: (string) $request->route('platformUuid'),
        );
    }
}
