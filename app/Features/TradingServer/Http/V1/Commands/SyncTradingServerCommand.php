<?php

namespace App\Features\TradingServer\Http\V1\Commands;

class SyncTradingServerCommand
{
    public function __construct(
        public string $TradingServerId,
    ) {}
}