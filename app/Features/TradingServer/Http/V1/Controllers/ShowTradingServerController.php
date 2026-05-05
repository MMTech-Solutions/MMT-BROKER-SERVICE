<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ShowTradingServerCommand;
use App\Features\TradingServer\Http\V1\Requests\ShowTradingServerRequest;
use App\Features\TradingServer\UseCases\ShowTradingServerUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ShowTradingServerController
{
    use ApiResponse;

    public function __invoke(
        ShowTradingServerRequest $request,
        ShowTradingServerUseCase $useCase,
    ): JsonResponse {
        $tradingServer = $useCase->execute(ShowTradingServerCommand::fromRequest($request));

        return $this->success($tradingServer->toArray());
    }
}
