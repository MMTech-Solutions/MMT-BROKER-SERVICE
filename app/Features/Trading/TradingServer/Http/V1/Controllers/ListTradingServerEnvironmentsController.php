<?php

namespace App\Features\Trading\TradingServer\Http\V1\Controllers;

use App\Features\Trading\TradingServer\Enums\EnvironmentEnum;
use App\Features\Trading\TradingServer\Http\V1\Requests\ListTradingServerEnvironmentsRequest;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListTradingServerEnvironmentsController
{
    use ApiResponse;
    
    public function __invoke(ListTradingServerEnvironmentsRequest $request): JsonResponse
    {
        return $this->success(EnvironmentEnum::serialized());
    }
}