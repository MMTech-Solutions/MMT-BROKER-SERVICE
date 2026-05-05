<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListSecuritiesCommand;
use App\Features\TradingServer\Http\V1\Requests\ListSecuritiesRequest;
use App\Features\TradingServer\UseCases\ListSecuritiesUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSecuritiesController
{
    use ApiResponse;

    public function __invoke(
        ListSecuritiesRequest $request,
        ListSecuritiesUseCase $useCase,
    ): JsonResponse {
        $securitiesDTO = $useCase->execute(ListSecuritiesCommand::fromRequest($request));
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
