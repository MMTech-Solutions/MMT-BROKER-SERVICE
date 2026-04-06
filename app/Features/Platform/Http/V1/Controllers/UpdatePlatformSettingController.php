<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\UpdatePlatformSettingCommand;
use App\Features\Platform\Http\V1\Requests\UpdatePlatformSettingRequest;
use App\Features\Platform\Http\V1\Resources\PlatformSettingResource;
use App\Features\Platform\UseCases\UpdatePlatformSettingUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class UpdatePlatformSettingController
{
    use ApiResponse;

    public function __invoke(
        UpdatePlatformSettingRequest $request,
        UpdatePlatformSettingUseCase $useCase,
    ): JsonResponse {
        $setting = $useCase->execute(UpdatePlatformSettingCommand::fromRequest($request));

        return $this->success((new PlatformSettingResource($setting))->resolve());
    }
}
