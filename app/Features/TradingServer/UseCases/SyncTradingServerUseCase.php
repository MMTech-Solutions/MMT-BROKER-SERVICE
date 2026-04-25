<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Http\V1\Commands\SyncTradingServerCommand;
use App\Features\TradingServer\Jobs\SyncTradingServerJob;

class SyncTradingServerUseCase
{
    public function execute(SyncTradingServerCommand $command)
    {
        SyncTradingServerJob::dispatch($command->TradingServerId);
    }
}