<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\ListLeveragesCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\ListLeveragesRequest;
use App\Features\Trading\Leverage\Http\V1\Resources\LeverageResource;
use App\Features\Trading\Leverage\UseCases\ListLeveragesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListLeveragesController
{
    use ApiResponse;

    public function __invoke(
        ListLeveragesRequest $request,
        ListLeveragesUseCase $useCase,
    ): JsonResponse {
        $leverages = $useCase->execute(ListLeveragesCommand::fromRequest($request));

        return $this->success(LeverageResource::collection($leverages)->resolve());
    }
}
