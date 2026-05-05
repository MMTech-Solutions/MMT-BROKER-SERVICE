<?php

namespace App\Features\Trading\TradingServer\Http\V1\Controllers;

use App\Features\Trading\TradingServer\Http\V1\Commands\ListServerGroupsCommand;
use App\Features\Trading\TradingServer\Http\V1\Requests\ListServerGroupsRequest;
use App\Features\Trading\TradingServer\UseCases\ListServerGroupsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupsController
{
    use ApiResponse;

    public function __invoke(
        ListServerGroupsRequest $request,
        ListServerGroupsUseCase $useCase,
    ): JsonResponse {
        $serverGroupsDTO = $useCase->execute(ListServerGroupsCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $serverGroupsDTO->items();
        $data = $serverGroupsDTO->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only(['name', 'meta_name']),
        );
    }
}
