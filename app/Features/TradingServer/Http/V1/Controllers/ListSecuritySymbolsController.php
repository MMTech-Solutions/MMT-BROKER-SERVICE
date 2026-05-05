<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\ListSecuritySymbolsCommand;
use App\Features\TradingServer\Http\V1\Requests\ListSecuritySymbolsRequest;
use App\Features\TradingServer\UseCases\ListSecuritySymbolsUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSecuritySymbolsController
{
    use ApiResponse;

    public function __invoke(
        ListSecuritySymbolsRequest $request,
        ListSecuritySymbolsUseCase $useCase,
    ): JsonResponse {
        $symbolsDTO = $useCase->execute(ListSecuritySymbolsCommand::fromRequest($request));
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
