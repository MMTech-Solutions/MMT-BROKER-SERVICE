<?php

namespace App\Features\Trading\TradingServer\Http\V1\Commands;

use App\Features\Trading\TradingServer\Http\V1\Requests\ListSecuritySymbolsRequest;

class ListSecuritySymbolsCommand
{
    public function __construct(
        public readonly string $TradingServerId,
        public readonly string $securityId,
        public readonly ?string $name,
        public readonly ?string $alpha,
        public readonly ?int $stype,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListSecuritySymbolsRequest $request): self
    {
        /** @var array{name?: string, alpha?: string, stype?: int, per_page?: int} $validated */
        $validated = $request->validated();

        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
            securityId: (string) $request->route('securityUuid'),
            name: $validated['name'] ?? null,
            alpha: $validated['alpha'] ?? null,
            stype: array_key_exists('stype', $validated) ? (int) $validated['stype'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
