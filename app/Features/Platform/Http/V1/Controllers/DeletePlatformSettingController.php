<?php

namespace App\Features\Platform\Http\V1\Controllers;

use App\Features\Platform\Http\V1\Commands\DeletePlatformSettingCommand;
use App\Features\Platform\Http\V1\Requests\DeletePlatformSettingRequest;
use App\Features\Platform\UseCases\DeletePlatformSettingUseCase;
use Illuminate\Http\Response;
use MMT\ApiResponseNormalizer\ApiResponse;

class DeletePlatformSettingController
{
    use ApiResponse;

    public function __invoke(
        DeletePlatformSettingRequest $request,
        DeletePlatformSettingUseCase $useCase,
    ): Response {
        $useCase->execute(DeletePlatformSettingCommand::fromRequest($request));

        return $this->noContent();
    }
}
