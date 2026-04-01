<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\UpdatePlatformCommand;
use App\Features\Platform\Http\V1\Requests\UpdatePlatformRequest;
use App\Features\Platform\Http\V1\Resources\PlatformResource;
use App\Features\Platform\UseCases\UpdatePlatformUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class UpdatePlatformController
{
    use ApiResponse;

    public function __invoke(
        UpdatePlatformRequest $request,
        UpdatePlatformUseCase $useCase,
    ): JsonResponse {
        $platform = $useCase->execute(UpdatePlatformCommand::fromRequest($request));

        return $this->success((new PlatformResource($platform))->resolve());
    }
}
