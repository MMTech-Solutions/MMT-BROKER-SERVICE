<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Enums\EnvironmentEnum;
use App\Features\Manager\Http\V1\Requests\ListManagerEnvironmentsRequest;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListManagerEnvironmentsController
{
    use ApiResponse;
    
    public function __invoke(ListManagerEnvironmentsRequest $request): JsonResponse
    {
        return $this->success(EnvironmentEnum::serialized());
    }
}