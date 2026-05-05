<?php

namespace App\Features\Trading\Platform\Http\V1\Controllers;

use App\Features\Trading\Platform\Http\V1\Requests\ListAvailablePlatformsRequest;
use MMT\ApiResponseNormalizer\ApiResponse;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;

class ListAvailablePlatformsController
{
    use ApiResponse;


    public function __invoke(ListAvailablePlatformsRequest $request)
    {
        return $this->success(PlatformEnum::serialized());
    }
}