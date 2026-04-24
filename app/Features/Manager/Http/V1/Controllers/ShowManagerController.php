<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\ShowManagerCommand;
use App\Features\Manager\Http\V1\Requests\ShowManagerRequest;
use App\Features\Manager\Http\V1\Resources\ManagerResource;
use App\Features\Manager\UseCases\ShowManagerUseCase;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class ShowManagerController
{
    use ApiResponse;

    public function __invoke(
        ShowManagerRequest $request,
        ShowManagerUseCase $useCase,
    ): JsonResponse {
        $manager = $useCase->execute(ShowManagerCommand::fromRequest($request));

        return $this->success((new ManagerResource($manager))->resolve());
    }
}
