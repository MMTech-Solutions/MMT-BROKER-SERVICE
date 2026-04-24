<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ListManagersRequest;

class ListManagersCommand
{
    public function __construct(
        public readonly ?string $platformId,
        public readonly ?string $host,
        public readonly ?string $username,
        public readonly ?int $port,
        public readonly ?int $enviroment,
        public readonly ?bool $isActive,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListManagersRequest $request): self
    {
        /** @var array{host?: string, username?: string, port?: int, enviroment?: int, is_active?: bool, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            platformId: $request->route('platformUuid'),
            host: $validated['host'] ?? null,
            username: $validated['username'] ?? null,
            port: array_key_exists('port', $validated) ? (int) $validated['port'] : null,
            enviroment: array_key_exists('enviroment', $validated) ? (int) $validated['enviroment'] : null,
            isActive: array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
