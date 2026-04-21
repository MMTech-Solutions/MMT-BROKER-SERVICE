<?php

namespace App\Features\Platform\Http\V1\Commands;

use App\Features\Platform\Http\V1\Requests\StorePlatformSettingRequest;

class StorePlatformSettingCommand
{
    public function __construct(
        public readonly string $platformId,
        public readonly string $host,
        public readonly int $port,
        public readonly string $username,
        public readonly string $password,
        public readonly int $environment,
        public readonly bool $isActive,
    ) {}

    public static function fromRequest(StorePlatformSettingRequest $request): self
    {
        /** @var array{host: string, port: int, username: string, password: string, environment: int, is_active?: bool} $validated */
        $validated = $request->validated();

        return new self(
            platformId: (string) $request->route('platformUuid'),
            host: $validated['host'],
            port: (int) $validated['port'],
            username: $validated['username'],
            password: $validated['password'],
            environment: (int) $validated['environment'],
            isActive: (bool) ($validated['is_active'] ?? false),
        );
    }
}
