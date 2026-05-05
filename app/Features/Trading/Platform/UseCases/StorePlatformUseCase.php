<?php

namespace App\Features\Trading\Platform\UseCases;

use App\Features\Trading\Platform\DTOs\PlatformDTO;
use App\Features\Trading\Platform\Http\V1\Commands\StorePlatformCommand;
use App\Features\Trading\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Trading\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class StorePlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(StorePlatformCommand $command): PlatformDTO
    {
        return PlatformDTO::from($this->platformRepository->create([
            'name' => $command->name,
            'custom_name' => $command->customName,
            'volume_factor' => $command->volumeFactor,
            'is_active' => $command->isActive,
        ]));
    }
}
