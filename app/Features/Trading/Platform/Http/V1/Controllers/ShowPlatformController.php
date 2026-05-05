<?php

namespace App\Features\Trading\Platform\Http\V1\Controllers;

use App\Features\Trading\Platform\Http\V1\Commands\ShowPlatformCommand;
use App\Features\Trading\Platform\Http\V1\Requests\ShowPlatformRequest;
use App\Features\Trading\Platform\Http\V1\Resources\PlatformResource;
use App\Features\Trading\Platform\UseCases\ShowPlatformUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ShowPlatformController
{
    use ApiResponse;

    public function __invoke(
        ShowPlatformRequest $request,
        ShowPlatformUseCase $useCase,
    ): JsonResponse {
        $platform = $useCase->execute(ShowPlatformCommand::fromRequest($request));

        return $this->success($platform->toArray());
    }
}
