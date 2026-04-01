<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\DeletePlatformCommand;
use App\Features\Platform\Http\V1\Requests\DeletePlatformRequest;
use App\Features\Platform\UseCases\DeletePlatformUseCase;
use Illuminate\Http\Response;
use MMT\ApiResponseNormalizer\ApiResponse;

class DeletePlatformController
{
    use ApiResponse;

    public function __invoke(
        DeletePlatformRequest $request,
        DeletePlatformUseCase $useCase,
    ): Response {
        $useCase->execute(DeletePlatformCommand::fromRequest($request));

        return $this->noContent();
    }
}
