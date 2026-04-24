<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\ListManagersCommand;
use App\Features\Manager\Http\V1\Requests\ListManagersRequest;
use App\Features\Manager\Http\V1\Resources\ManagerResource;
use App\Features\Manager\UseCases\ListManagersUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListManagersController
{
    use ApiResponse;

    public function __invoke(
        ListManagersRequest $request,
        ListManagersUseCase $useCase,
    ): JsonResponse {
        $managers = $useCase->execute(ListManagersCommand::fromRequest($request));

        return $this->success(
            data: ManagerResource::collection(collect($managers->items()))->resolve(),
            paginator: $managers,
            filters: $request->safe()->only(['platform_id', 'host', 'username', 'port', 'enviroment', 'is_active']),
        );
    }
}
