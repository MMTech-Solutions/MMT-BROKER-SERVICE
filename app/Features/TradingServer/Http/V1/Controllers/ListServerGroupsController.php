<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListServerGroupsCommand;
use App\Features\TradingServer\Http\V1\Requests\ListServerGroupsRequest;
use App\Features\TradingServer\Http\V1\Resources\ServerGroupResource;
use App\Features\TradingServer\UseCases\ListServerGroupsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListServerGroupsController
{
    use ApiResponse;

    public function __invoke(
        ListServerGroupsRequest $request,
        ListServerGroupsUseCase $useCase,
    ): JsonResponse {
        $serverGroups = $useCase->execute(ListServerGroupsCommand::fromRequest($request));

        return $this->success(
            data: ServerGroupResource::collection(collect($serverGroups->items()))->resolve(),
            paginator: $serverGroups,
            filters: $request->safe()->only(['name', 'meta_name']),
        );
    }
}
