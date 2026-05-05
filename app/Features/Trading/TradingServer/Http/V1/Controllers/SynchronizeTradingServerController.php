<?php

namespace App\Features\Trading\TradingServer\Http\V1\Controllers;

use App\Features\Trading\TradingServer\Http\V1\Commands\SyncTradingServerCommand;
use App\Features\Trading\TradingServer\Http\V1\Requests\SyncTradingServerRequest;
use App\Features\Trading\TradingServer\UseCases\SyncTradingServerUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class SynchronizeTradingServerController
{
    use ApiResponse;

    public function __invoke(
        SyncTradingServerRequest $request,
        SyncTradingServerUseCase $syncTradingServerUseCase,
    ) {
        $command = SyncTradingServerCommand::fromRequest($request);

        $syncTradingServerUseCase->execute($command);

        return $command->isAsync ? $this->accepted() : $this->noContent();
    }
}
