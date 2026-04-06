<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\UpdatePlatformSettingRequest;

class UpdatePlatformSettingCommand
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $platformId,
        public readonly string $settingId,
        public readonly array $attributes,
    ) {}

    public static function fromRequest(UpdatePlatformSettingRequest $request): self
    {
        return new self(
            platformId: (string) $request->route('platformUuid'),
            settingId: (string) $request->route('settingUuid'),
            attributes: $request->validated(),
        );
    }
}
