<?php

namespace App\Features\Leverage\Http\V1\Controllers;

use App\Features\Leverage\Http\V1\Commands\DeleteLeverageCommand;
use App\Features\Leverage\Http\V1\Requests\DeleteLeverageRequest;
use App\Features\Leverage\UseCases\DeleteLeverageUseCase;
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
