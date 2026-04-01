<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\UpdatePlatformCommand;
use App\Features\Platform\Models\Platform;

class UpdatePlatformUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(UpdatePlatformCommand $command): Platform
    {
        $repository = $this->platformFactory->make();
        $platform = $repository->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }
        
        return $repository->update($platform, $command->attributes);
    }
}
