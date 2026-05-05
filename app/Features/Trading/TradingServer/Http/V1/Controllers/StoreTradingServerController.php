<?php

namespace App\Features\Trading\TradingServer\Http\V1\Controllers;

use App\Features\Trading\TradingServer\Http\V1\Commands\StoreTradingServerCommand;
use App\Features\Trading\TradingServer\Http\V1\Requests\StoreTradingServerRequest;
use App\Features\Trading\TradingServer\UseCases\StoreTradingServerUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class StoreTradingServerController
{
    use ApiResponse;

    public function __invoke(
        StoreTradingServerRequest $request,
        StoreTradingServerUseCase $useCase,
    ): JsonResponse {
        $tradingServer = $useCase->execute(StoreTradingServerCommand::fromRequest($request));
        return $this->created($tradingServer->toArray());
    }
}
