<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListTradingServersCommand;
use App\Features\TradingServer\Http\V1\Requests\ListTradingServersRequest;
use App\Features\TradingServer\UseCases\ListTradingServersUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListTradingServersController
{
    use ApiResponse;

    public function __invoke(
        ListTradingServersRequest $request,
        ListTradingServersUseCase $useCase,
    ): JsonResponse {

        $tradingServers = $useCase->execute(ListTradingServersCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $tradingServers->items();
        $data = $tradingServers->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only(['platform_id', 'host', 'username', 'port', 'enviroment', 'is_active']),
        );
    }
}
