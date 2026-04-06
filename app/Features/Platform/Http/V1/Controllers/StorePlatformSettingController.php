<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\StorePlatformSettingCommand;
use App\Features\Platform\Http\V1\Requests\StorePlatformSettingRequest;
use App\Features\Platform\Http\V1\Resources\PlatformSettingResource;
use App\Features\Platform\UseCases\StorePlatformSettingUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class StorePlatformSettingController
{
    use ApiResponse;

    public function __invoke(
        StorePlatformSettingRequest $request,
        StorePlatformSettingUseCase $useCase,
    ): JsonResponse {
        $setting = $useCase->execute(StorePlatformSettingCommand::fromRequest($request));

        return $this->created((new PlatformSettingResource($setting))->resolve());
    }
}
