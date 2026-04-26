<?php

namespace App\Features\Leverage\Http\V1\Controllers;

use App\Features\Leverage\Http\V1\Commands\ListServerGroupLeveragesCommand;
use App\Features\Leverage\Http\V1\Requests\ListServerGroupLeveragesRequest;
use App\Features\Leverage\Http\V1\Resources\LeverageResource;
use App\Features\Leverage\UseCases\ListServerGroupLeveragesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListServerGroupLeveragesController
{
    use ApiResponse;

    public function __invoke(
        ListServerGroupLeveragesRequest $request,
        ListServerGroupLeveragesUseCase $useCase,
    ): JsonResponse {
        $leverages = $useCase->execute(ListServerGroupLeveragesCommand::fromRequest($request));

        return $this->success(
            data: LeverageResource::collection(collect($leverages->items()))->resolve(),
            paginator: $leverages,
            filters: $request->safe()->only(['name', 'value']),
        );
    }
}
