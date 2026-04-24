<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\UpdateManagerCommand;
use App\Features\Manager\Http\V1\Requests\UpdateManagerRequest;
use App\Features\Manager\Http\V1\Resources\ManagerResource;
use App\Features\Manager\UseCases\UpdateManagerUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class UpdateManagerController
{
    use ApiResponse;

    public function __invoke(
        UpdateManagerRequest $request,
        UpdateManagerUseCase $useCase,
    ): JsonResponse {
        $manager = $useCase->execute(UpdateManagerCommand::fromRequest($request));

        return $this->success((new ManagerResource($manager))->resolve());
    }
}
