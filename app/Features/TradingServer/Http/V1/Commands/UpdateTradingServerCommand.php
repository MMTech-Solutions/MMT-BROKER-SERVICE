<?php

namespace App\Features\TradingServer\Http\V1\Commands;

use App\Features\TradingServer\Enums\EnvironmentEnum;
use App\Features\TradingServer\Http\V1\Requests\UpdateTradingServerRequest;

class UpdateTradingServerCommand
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $TradingServerId,
        public readonly ?string $host,
        public readonly ?int $port,
        public readonly ?string $username,
        public readonly ?string $password,
        public readonly ?EnvironmentEnum $environment,
        public readonly ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateTradingServerRequest $request): self
    {
        $validated = $request->validated();
        $environment = $validated['environment'] ? EnvironmentEnum::from($validated['environment']) : null;
        
        return new self(
            TradingServerId: $validated['trading_server_id'],
            host: $validated['host'] ?? null,
            port: $validated['port'] ?? null,
            username: $validated['username'] ?? null,
            password: $validated['password'] ?? null,
            environment: $environment,
            isActive: $validated['is_active'] ?? false,
        );
    }
}
