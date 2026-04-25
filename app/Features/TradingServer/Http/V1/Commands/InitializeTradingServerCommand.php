<?php

namespace App\Features\TradingServer\Http\V1\Commands;

use App\Features\TradingServer\Http\V1\Requests\InitializeTradingServerRequest;

class InitializeTradingServerCommand
{
    public function __construct(
        public readonly string $TradingServerId
    ) {}

    public static function fromRequest(InitializeTradingServerRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            TradingServerId: $validated['trading_server_id']
        );
    }
}