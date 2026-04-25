<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListSecuritySymbolsCommand;
use App\Features\TradingServer\Http\V1\Requests\ListSecuritySymbolsRequest;
use App\Features\TradingServer\Http\V1\Resources\SymbolResource;
use App\Features\TradingServer\UseCases\ListSecuritySymbolsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListSecuritySymbolsController
{
    use ApiResponse;

    public function __invoke(
        ListSecuritySymbolsRequest $request,
        ListSecuritySymbolsUseCase $useCase,
    ): JsonResponse {
        $symbols = $useCase->execute(ListSecuritySymbolsCommand::fromRequest($request));

        return $this->success(
            data: SymbolResource::collection(collect($symbols->items()))->resolve(),
            paginator: $symbols,
            filters: $request->safe()->only(['name', 'alpha', 'stype']),
        );
    }
}
