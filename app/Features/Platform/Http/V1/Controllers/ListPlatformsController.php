<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\ListPlatformsCommand;
use App\Features\Platform\Http\V1\Requests\ListPlatformsRequest;
use App\Features\Platform\UseCases\ListPlatformsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListPlatformsController
{
    use ApiResponse;

    public function __invoke(
        ListPlatformsRequest $request,
        ListPlatformsUseCase $useCase
    ): JsonResponse 
    {
        $platforms = $useCase->execute(ListPlatformsCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $platforms->items();
        $data = $platforms->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only(['name', 'custom_name', 'code', 'volume_factor', 'is_active']),
        );
    }
}
