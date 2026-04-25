<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListTradingServersCommand;
use App\Features\TradingServer\Http\V1\Requests\ListTradingServersRequest;
use App\Features\TradingServer\Http\V1\Resources\TradingServerResource;
use App\Features\TradingServer\UseCases\ListTradingServersUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListTradingServersController
{
    use ApiResponse;

    public function __invoke(
        ListTradingServersRequest $request,
        ListTradingServersUseCase $useCase,
    ): JsonResponse {
        $TradingServers = $useCase->execute(ListTradingServersCommand::fromRequest($request));

        return $this->success(
            data: TradingServerResource::collection(collect($TradingServers->items()))->resolve(),
            paginator: $TradingServers,
            filters: $request->safe()->only(['platform_id', 'host', 'username', 'port', 'enviroment', 'is_active']),
        );
    }
}
