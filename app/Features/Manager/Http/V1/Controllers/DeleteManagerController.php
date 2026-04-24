<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\DeleteManagerCommand;
use App\Features\Manager\Http\V1\Requests\DeleteManagerRequest;
use App\Features\Manager\UseCases\DeleteManagerUseCase;
use Illuminate\Http\Response;
use MMT\ApiResponseNormalizer\ApiResponse;

class DeleteManagerController
{
    use ApiResponse;

    public function __invoke(
        DeleteManagerRequest $request,
        DeleteManagerUseCase $useCase,
    ): Response {
        $useCase->execute(DeleteManagerCommand::fromRequest($request));

        return $this->noContent();
    }
}
