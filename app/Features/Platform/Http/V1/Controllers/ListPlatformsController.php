<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\ListPlatformsCommand;
use App\Features\Platform\Http\V1\Requests\ListPlatformsRequest;
use App\Features\Platform\Http\V1\Resources\PlatformResource;
use App\Features\Platform\UseCases\ListPlatformsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListPlatformsController
{
    use ApiResponse;

    public function __invoke(
        ListPlatformsRequest $request,
        ListPlatformsUseCase $useCase,
    ): JsonResponse {
        $platforms = $useCase->execute(ListPlatformsCommand::fromRequest($request));

        return $this->success(
            data: PlatformResource::collection(collect($platforms->items()))->resolve(),
            paginator: $platforms,
            filters: $request->safe()->only(['name', 'custom_name', 'code', 'volume_factor', 'is_active']),
        );
    }
}
