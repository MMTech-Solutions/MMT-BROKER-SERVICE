<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\InitializeTradingServerCommand;
use App\Features\TradingServer\Http\V1\Requests\InitializeTradingServerRequest;
use App\Features\TradingServer\UseCases\InitializeTradingServerUserCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class InitializeTradingServerController
{
    use ApiResponse;

    public function __invoke(
        InitializeTradingServerRequest $request,
        InitializeTradingServerUserCase $initializeTradingServerUserCase
    )
    {
        $command = InitializeTradingServerCommand::fromRequest($request);
        
        $initializeTradingServerUserCase->execute($command);

        return $this->accepted();
    }
}