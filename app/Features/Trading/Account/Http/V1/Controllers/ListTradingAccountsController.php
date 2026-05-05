<?php

namespace App\Features\Trading\Account\Http\V1\Controllers;

use App\Features\Trading\Account\Http\V1\Commands\ListTradingAccountsCommand;
use App\Features\Trading\Account\Http\V1\Requests\ListTradingAccountsRequest;
use App\Features\Trading\Account\UseCases\ListTradingAccountsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use MMT\ApiResponseNormalizer\ApiResponse;

class ListTradingAccountsController
{
    use ApiResponse;

    public function __invoke(
        ListTradingAccountsRequest $request,
        ListTradingAccountsUseCase $useCase
    ): JsonResponse {
        $accounts = $useCase->execute(ListTradingAccountsCommand::fromRequest($request));
        /** @var LengthAwarePaginator */
        $paginator = $accounts->items();
        $data = $accounts->toArray()['data'];

        return $this->success(
            data: $data,
            paginator: $paginator,
            filters: $request->safe()->only([
                'external_user_id',
                'external_trader_id',
                'server_group_id',
                'is_active',
            ]),
        );
    }
}
