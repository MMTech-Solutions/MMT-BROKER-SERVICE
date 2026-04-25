<?php

namespace App\Features\TradingServer\Http\V1\Commands;

use App\Features\TradingServer\Http\V1\Requests\ListServerGroupSecuritiesRequest;

class ListServerGroupSecuritiesCommand
{
    public function __construct(
        public readonly string $TradingServerId,
        public readonly string $serverGroupId,
        public readonly ?string $name,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListServerGroupSecuritiesRequest $request): self
    {
        /** @var array{name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
            serverGroupId: (string) $request->route('serverGroupUuid'),
            name: $validated['name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
