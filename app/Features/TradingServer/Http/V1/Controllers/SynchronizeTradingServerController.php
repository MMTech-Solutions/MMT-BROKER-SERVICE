<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Requests\SyncTradingServerRequest;
use App\Features\TradingServer\Http\V1\Commands\SyncTradingServerCommand;
use App\Features\TradingServer\UseCases\SyncTradingServerUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;


class SynchronizeTradingServerController
{
    use ApiResponse;

    public function __invoke(
        SyncTradingServerRequest $request,
        SyncTradingServerUseCase $syncTradingServerUseCase,
    )
    {
        $command = new SyncTradingServerCommand(
            TradingServerId: $request->route('tradingServerUuid'),
        );

        $syncTradingServerUseCase->execute($command);

        return $this->accepted();
    }
}