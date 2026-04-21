<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\ShowPlatformCommand;
use App\Features\Platform\Models\Platform;

class ShowPlatformUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(ShowPlatformCommand $command): Platform
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException();
        }

        return $platform;
    }
}
