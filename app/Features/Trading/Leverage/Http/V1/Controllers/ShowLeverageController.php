<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\ShowLeverageCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\ShowLeverageRequest;
use App\Features\Trading\Leverage\Http\V1\Resources\LeverageResource;
use App\Features\Trading\Leverage\UseCases\ShowLeverageUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ShowLeverageController
{
    use ApiResponse;

    public function __invoke(
        ShowLeverageRequest $request,
        ShowLeverageUseCase $useCase,
    ): JsonResponse {
        $leverage = $useCase->execute(ShowLeverageCommand::fromRequest($request));

        return $this->success((new LeverageResource($leverage))->resolve());
    }
}
