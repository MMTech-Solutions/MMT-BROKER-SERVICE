<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Http\V1\Commands\InitializeTradingServerCommand;
use App\Features\TradingServer\Jobs\InitializeGroupJob;

class InitializeTradingServerUserCase
{
    public function execute(InitializeTradingServerCommand $command)
    {
        InitializeGroupJob::dispatch($command->TradingServerId);
    }
}