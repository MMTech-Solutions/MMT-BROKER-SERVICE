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
        public readonly ?string $host,
        public readonly ?int $port,
        public readonly ?string $username,
        public readonly ?string $password,
        public readonly ?int $environment,
        public readonly ?bool $isActive,
    ) {}

    public static function fromRequest(UpdatePlatformSettingRequest $request): self
    {
        $validated = $request->validated();
        
        return new self(
            platformId: (string) $request->route('platformUuid'),
            settingId: (string) $request->route('settingUuid'),
            host: $validated['host'] ?? null,
            port: $validated['port'] ?? null,
            username: $validated['username'] ?? null,
            password: $validated['password'] ?? null,
            environment: $validated['environment'] ?? null,
            isActive: $validated['is_active'] ?? false,
        );
    }
}
