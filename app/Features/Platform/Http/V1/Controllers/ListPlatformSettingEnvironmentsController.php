<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Enums\PlatformEnvironment;
use App\Features\Platform\Http\V1\Requests\ListPlatformSettingEnvironmentsRequest;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListPlatformSettingEnvironmentsController
{
    use ApiResponse;
    
    public function __invoke(ListPlatformSettingEnvironmentsRequest $request): JsonResponse
    {
        return $this->success(PlatformEnvironment::serialized());
    }
}