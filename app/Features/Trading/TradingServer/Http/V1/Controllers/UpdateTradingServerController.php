<?php

namespace App\Features\Trading\TradingServer\Http\V1\Controllers;

use App\Features\Trading\TradingServer\Http\V1\Commands\UpdateTradingServerCommand;
use App\Features\Trading\TradingServer\Http\V1\Requests\UpdateTradingServerRequest;
use App\Features\Trading\TradingServer\UseCases\UpdateTradingServerUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class UpdateTradingServerController
{
    use ApiResponse;

    public function __invoke(
        UpdateTradingServerRequest $request,
        UpdateTradingServerUseCase $useCase,
    ): JsonResponse {
        $tradingServerDTO = $useCase->execute(UpdateTradingServerCommand::fromRequest($request));

        return $this->success($tradingServerDTO->toArray());
    }
}
