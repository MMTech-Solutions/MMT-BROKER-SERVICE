<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\ShowPlatformSettingRequest;

class ShowPlatformSettingCommand
{
    public function __construct(
        public readonly string $platformId,
        public readonly string $settingId,
    ) {}

    public static function fromRequest(ShowPlatformSettingRequest $request): self
    {
        return new self(
            platformId: (string) $request->route('platformUuid'),
            settingId: (string) $request->route('settingUuid'),
        );
    }
}
