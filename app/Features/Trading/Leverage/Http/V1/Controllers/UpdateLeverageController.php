<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\UpdateLeverageCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\UpdateLeverageRequest;
use App\Features\Trading\Leverage\Http\V1\Resources\LeverageResource;
use App\Features\Trading\Leverage\UseCases\UpdateLeverageUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class UpdateLeverageController
{
    use ApiResponse;

    public function __invoke(
        UpdateLeverageRequest $request,
        UpdateLeverageUseCase $useCase,
    ): JsonResponse {
        $leverage = $useCase->execute(UpdateLeverageCommand::fromRequest($request));

        return $this->success((new LeverageResource($leverage))->resolve());
    }
}
