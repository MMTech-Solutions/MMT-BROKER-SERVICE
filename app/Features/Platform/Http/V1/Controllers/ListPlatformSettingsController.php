<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\ListPlatformSettingsCommand;
use App\Features\Platform\Http\V1\Requests\ListPlatformSettingsRequest;
use App\Features\Platform\Http\V1\Resources\PlatformSettingResource;
use App\Features\Platform\UseCases\ListPlatformSettingsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListPlatformSettingsController
{
    use ApiResponse;

    public function __invoke(
        ListPlatformSettingsRequest $request,
        ListPlatformSettingsUseCase $useCase,
    ): JsonResponse {
        $settings = $useCase->execute(ListPlatformSettingsCommand::fromRequest($request));

        return $this->success(
            data: PlatformSettingResource::collection(collect($settings->items()))->resolve(),
            paginator: $settings,
            filters: $request->safe()->only(['host', 'username', 'port', 'enviroment', 'is_active']),
        );
    }
}
