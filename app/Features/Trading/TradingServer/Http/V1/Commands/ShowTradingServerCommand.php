<?php

namespace App\Features\Trading\TradingServer\Http\V1\Commands;

use App\Features\Trading\TradingServer\Http\V1\Requests\ShowTradingServerRequest;

class ShowTradingServerCommand
{
    public function __construct(
        public readonly string $TradingServerId,
    ) {}

    public static function fromRequest(ShowTradingServerRequest $request): self
    {
        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
        );
    }
}
