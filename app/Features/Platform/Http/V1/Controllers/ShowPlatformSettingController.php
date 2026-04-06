<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\ShowPlatformSettingCommand;
use App\Features\Platform\Http\V1\Requests\ShowPlatformSettingRequest;
use App\Features\Platform\Http\V1\Resources\PlatformSettingResource;
use App\Features\Platform\UseCases\ShowPlatformSettingUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ShowPlatformSettingController
{
    use ApiResponse;

    public function __invoke(
        ShowPlatformSettingRequest $request,
        ShowPlatformSettingUseCase $useCase,
    ): JsonResponse {
        $setting = $useCase->execute(ShowPlatformSettingCommand::fromRequest($request));

        return $this->success((new PlatformSettingResource($setting))->resolve());
    }
}
