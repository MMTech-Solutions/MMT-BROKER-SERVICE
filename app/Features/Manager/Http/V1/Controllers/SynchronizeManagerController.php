<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Requests\SyncManagerRequest;
use App\Features\Manager\Http\V1\Commands\SyncManagerCommand;
use App\Features\Manager\UseCases\SyncManagerUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;


class SynchronizeManagerController
{
    use ApiResponse;

    public function __invoke(
        SyncManagerRequest $request,
        SyncManagerUseCase $syncManagerUseCase,
    )
    {
        $command = new SyncManagerCommand(
            managerId: $request->route('managerUuid'),
        );

        $syncManagerUseCase->execute($command);

        return $this->accepted();
    }
}