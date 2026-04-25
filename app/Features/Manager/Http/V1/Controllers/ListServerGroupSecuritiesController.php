<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\ListServerGroupSecuritiesCommand;
use App\Features\Manager\Http\V1\Requests\ListServerGroupSecuritiesRequest;
use App\Features\Manager\Http\V1\Resources\SecurityResource;
use App\Features\Manager\UseCases\ListServerGroupSecuritiesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListServerGroupSecuritiesController
{
    use ApiResponse;

    public function __invoke(
        ListServerGroupSecuritiesRequest $request,
        ListServerGroupSecuritiesUseCase $useCase,
    ): JsonResponse {
        $securities = $useCase->execute(ListServerGroupSecuritiesCommand::fromRequest($request));

        return $this->success(
            data: SecurityResource::collection(collect($securities->items()))->resolve(),
            paginator: $securities,
            filters: $request->safe()->only(['name']),
        );
    }
}
