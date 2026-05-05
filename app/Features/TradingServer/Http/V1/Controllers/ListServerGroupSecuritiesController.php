<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListServerGroupSecuritiesCommand;
use App\Features\TradingServer\Http\V1\Requests\ListServerGroupSecuritiesRequest;
use App\Features\TradingServer\UseCases\ListServerGroupSecuritiesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupSecuritiesController
{
    use ApiResponse;

    public function __invoke(
        ListServerGroupSecuritiesRequest $request,
        ListServerGroupSecuritiesUseCase $useCase,
    ): JsonResponse {
        $securitiesDTO = $useCase->execute(ListServerGroupSecuritiesCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $securitiesDTO->items();
        $data = $securitiesDTO->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only(['name']),
        );
    }
}
