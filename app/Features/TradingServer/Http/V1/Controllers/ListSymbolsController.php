<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListSymbolsCommand;
use App\Features\TradingServer\Http\V1\Requests\ListSymbolsRequest;
use App\Features\TradingServer\UseCases\ListSymbolsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListSymbolsController
{
    use ApiResponse;

    public function __invoke(
        ListSymbolsRequest $request,
        ListSymbolsUseCase $useCase,
    ): JsonResponse {
        $symbolsDTO = $useCase->execute(ListSymbolsCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $symbolsDTO->items();
        $data = $symbolsDTO->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only(['name', 'alpha', 'stype']),
        );
    }
}
