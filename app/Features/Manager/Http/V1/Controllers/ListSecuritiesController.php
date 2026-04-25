<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\ListSecuritiesCommand;
use App\Features\Manager\Http\V1\Requests\ListSecuritiesRequest;
use App\Features\Manager\Http\V1\Resources\SecurityResource;
use App\Features\Manager\UseCases\ListSecuritiesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListSecuritiesController
{
    use ApiResponse;

    public function __invoke(
        ListSecuritiesRequest $request,
        ListSecuritiesUseCase $useCase,
    ): JsonResponse {
        $securities = $useCase->execute(ListSecuritiesCommand::fromRequest($request));

        return $this->success(
            data: SecurityResource::collection(collect($securities->items()))->resolve(),
            paginator: $securities,
            filters: $request->safe()->only(['name']),
        );
    }
}
