<?php

namespace App\Features\Manager\Http\V1\Controllers;

use App\Features\Manager\Http\V1\Commands\InitializeManagerCommand;
use App\Features\Manager\Http\V1\Requests\InitializeManagerRequest;
use App\Features\Manager\UseCases\InitializeManagerUserCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class InitializeManagerController
{
    use ApiResponse;

    public function __invoke(
        InitializeManagerRequest $request,
        InitializeManagerUserCase $initializeManagerUserCase
    )
    {
        $command = InitializeManagerCommand::fromRequest($request);
        
        $initializeManagerUserCase->execute($command);

        return $this->accepted();
    }
}