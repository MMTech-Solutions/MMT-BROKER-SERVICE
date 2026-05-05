<?php

namespace App\Features\Trading\TradingServer\Http\V1\Commands;

use App\Features\Trading\TradingServer\Http\V1\Requests\ListServerGroupsRequest;

class ListServerGroupsCommand
{
    public function __construct(
        public readonly string $TradingServerId,
        public readonly ?string $name,
        public readonly ?string $metaName,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListServerGroupsRequest $request): self
    {
        /** @var array{name?: string, meta_name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
            name: $validated['name'] ?? null,
            metaName: $validated['meta_name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
