<?php

namespace App\Features\Trading\Platform\Http\V1\Controllers;

use App\Features\Trading\Platform\Http\V1\Commands\UpdatePlatformCommand;
use App\Features\Trading\Platform\Http\V1\Requests\UpdatePlatformRequest;
use App\Features\Trading\Platform\UseCases\UpdatePlatformUseCase;
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

        return $this->success($platform->toArray());
    }
}
