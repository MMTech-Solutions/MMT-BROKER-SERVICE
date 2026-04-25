<?php

namespace App\Features\TradingServer\Http\V1\Commands;

use App\Features\TradingServer\Http\V1\Requests\ListSecuritiesRequest;

class ListSecuritiesCommand
{
    public function __construct(
        public readonly string $TradingServerId,
        public readonly ?string $name,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListSecuritiesRequest $request): self
    {
        /** @var array{name?: string, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
            name: $validated['name'] ?? null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
