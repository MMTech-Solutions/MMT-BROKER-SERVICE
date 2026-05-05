<?php

namespace App\Features\TradingServer\Http\V1\Commands;

use App\Features\TradingServer\Http\V1\Requests\SyncTradingServerRequest;

class SyncTradingServerCommand
{
    public function __construct(
        public string $tradingServerId,
        public bool $isAsync = false,
    ) {}

    public static function fromRequest(SyncTradingServerRequest $request)
    {
        $validated = $request->validated();
        $isAsync = (bool) $request->header('X-Async', false);

        return new self(
            tradingServerId: $validated['trading_server_id'],
            isAsync: $isAsync,
        );
    }
}
