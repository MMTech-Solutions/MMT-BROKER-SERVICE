<?php

namespace App\Features\TradingServer\Http\V1\Controllers;

use App\Features\TradingServer\Http\V1\Commands\DeleteTradingServerCommand;
use App\Features\TradingServer\Http\V1\Requests\DeleteTradingServerRequest;
use App\Features\TradingServer\UseCases\DeleteTradingServerUseCase;
use Illuminate\Http\Response;
use MMT\ApiResponseNormalizer\ApiResponse;

class DeleteTradingServerController
{
    use ApiResponse;

    public function __invoke(
        DeleteTradingServerRequest $request,
        DeleteTradingServerUseCase $useCase,
    ): Response {
        $useCase->execute(DeleteTradingServerCommand::fromRequest($request));

        return $this->noContent();
    }
}
