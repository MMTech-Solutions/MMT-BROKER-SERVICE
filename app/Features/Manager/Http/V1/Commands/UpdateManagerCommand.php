<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Enums\EnvironmentEnum;
use App\Features\Manager\Http\V1\Requests\UpdateManagerRequest;

class UpdateManagerCommand
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $managerId,
        public readonly ?string $host,
        public readonly ?int $port,
        public readonly ?string $username,
        public readonly ?string $password,
        public readonly ?EnvironmentEnum $environment,
        public readonly ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateManagerRequest $request): self
    {
        $validated = $request->validated();
        $environment = $validated['environment'] ? EnvironmentEnum::from($validated['environment']) : null;
        
        return new self(
            managerId: $validated['manager_id'],
            host: $validated['host'] ?? null,
            port: $validated['port'] ?? null,
            username: $validated['username'] ?? null,
            password: $validated['password'] ?? null,
            environment: $environment,
            isActive: $validated['is_active'] ?? false,
        );
    }
}
