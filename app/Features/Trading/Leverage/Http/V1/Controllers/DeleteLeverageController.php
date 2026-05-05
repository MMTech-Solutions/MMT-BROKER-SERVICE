<?php

namespace App\Features\Trading\Leverage\Http\V1\Controllers;

use App\Features\Trading\Leverage\Http\V1\Commands\DeleteLeverageCommand;
use App\Features\Trading\Leverage\Http\V1\Requests\DeleteLeverageRequest;
use App\Features\Trading\Leverage\UseCases\DeleteLeverageUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class DeleteLeverageController
{
    use ApiResponse;

    public function __invoke(
        DeleteLeverageRequest $request,
        DeleteLeverageUseCase $useCase,
    ) {
        $useCase->execute(DeleteLeverageCommand::fromRequest($request));

        return $this->noContent();
    }
}
