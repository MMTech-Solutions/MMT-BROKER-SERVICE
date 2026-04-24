<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Http\V1\Commands\StorePlatformCommand;
use App\Features\Platform\Models\Platform;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class StorePlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(StorePlatformCommand $command): Platform
    {
        return $this->platformRepository->create([
            'name' => $command->name,
            'custom_name' => $command->customName,
            'volume_factor' => $command->volumeFactor,
            'is_active' => $command->isActive,
        ]);
    }
}
