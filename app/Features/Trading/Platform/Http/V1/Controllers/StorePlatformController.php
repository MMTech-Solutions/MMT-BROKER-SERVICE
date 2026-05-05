<?php

namespace App\Features\Trading\Platform\Http\V1\Controllers;

use App\Features\Trading\Platform\Http\V1\Commands\StorePlatformCommand;
use App\Features\Trading\Platform\Http\V1\Requests\StorePlatformRequest;
use App\Features\Trading\Platform\UseCases\StorePlatformUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class StorePlatformController
{
    use ApiResponse;

    public function __invoke(
        StorePlatformRequest $request,
        StorePlatformUseCase $useCase,
    ): JsonResponse {
        $platform = $useCase->execute(StorePlatformCommand::fromRequest($request));

        return $this->created($platform->toArray());
    }
}
