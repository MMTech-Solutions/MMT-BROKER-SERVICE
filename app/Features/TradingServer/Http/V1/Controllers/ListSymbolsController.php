<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListSymbolsCommand;
use App\Features\TradingServer\Http\V1\Requests\ListSymbolsRequest;
use App\Features\TradingServer\Http\V1\Resources\SymbolResource;
use App\Features\TradingServer\UseCases\ListSymbolsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListSymbolsController
{
    use ApiResponse;

    public function __invoke(
        ListSymbolsRequest $request,
        ListSymbolsUseCase $useCase,
    ): JsonResponse {
        $symbols = $useCase->execute(ListSymbolsCommand::fromRequest($request));

        return $this->success(
            data: SymbolResource::collection(collect($symbols->items()))->resolve(),
            paginator: $symbols,
            filters: $request->safe()->only(['name', 'alpha', 'stype']),
        );
    }
}
