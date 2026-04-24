<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\StoreManagerCommand;
use App\Features\Manager\Http\V1\Requests\StoreManagerRequest;
use App\Features\Manager\UseCases\StoreManagerUseCase;
use App\Features\Manager\Http\V1\Resources\ManagerResource;
use Illuminate\Http\JsonResponse;
use MMT\ApiResponseNormalizer\ApiResponse;

class StoreManagerController
{
    use ApiResponse;

    public function __invoke(
        StoreManagerRequest $request,
        StoreManagerUseCase $useCase,
    ): JsonResponse {
        $manager = $useCase->execute(StoreManagerCommand::fromRequest($request));

        return $this->created(new ManagerResource($manager)->resolve());
    }
}
