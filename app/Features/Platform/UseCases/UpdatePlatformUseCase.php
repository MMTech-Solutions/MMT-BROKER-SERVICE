<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Http\V1\Commands\UpdatePlatformCommand;
use App\Features\Platform\Models\Platform;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class UpdatePlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(UpdatePlatformCommand $command): Platform
    {
        $platform = $this->platformRepository->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }
        
        return $this->platformRepository->update($platform, $command->attributes);
    }
}
