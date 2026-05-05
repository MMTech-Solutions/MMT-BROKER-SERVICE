<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\StoreLeverageCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\StoreLeverageRequest;
use App\Features\Trading\Leverage\Http\V1\Resources\LeverageResource;
use App\Features\Trading\Leverage\UseCases\StoreLeverageUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class StoreLeverageController
{
    use ApiResponse;

    public function __invoke(
        StoreLeverageRequest $request,
        StoreLeverageUseCase $useCase,
    ): JsonResponse {
        $leverage = $useCase->execute(StoreLeverageCommand::fromRequest($request));

        return $this->created((new LeverageResource($leverage))->resolve());
    }
}
