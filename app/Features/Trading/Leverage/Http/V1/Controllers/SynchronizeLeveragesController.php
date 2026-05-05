<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\SyncLeveragesCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\SyncLeveragesRequest;
use App\Features\Trading\Leverage\UseCases\SyncLeveragesUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class SynchronizeLeveragesController
{
    use ApiResponse;

    public function __invoke(
        SyncLeveragesRequest $request,
        SyncLeveragesUseCase $useCase,
    ) {
        $useCase->execute(SyncLeveragesCommand::fromRequest($request));

        return $this->noContent();
    }
}
