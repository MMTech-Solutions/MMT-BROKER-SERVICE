<?php

namespace App\Features\Trading\TradingServer\Http\V1\Commands;

use App\Features\Trading\TradingServer\Http\V1\Requests\DeleteTradingServerRequest;

class DeleteTradingServerCommand
{
    public function __construct(
        public readonly string $TradingServerId,
    ) {}

    public static function fromRequest(DeleteTradingServerRequest $request): self
    {
        return new self(
            TradingServerId: (string) $request->route('tradingServerUuid'),
        );
    }
}
