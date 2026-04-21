<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\StorePlatformCommand;
use App\Features\Platform\Models\Platform;

class StorePlatformUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(StorePlatformCommand $command): Platform
    {
        return $this->platformFactory->make()->create([
            'name' => $command->name,
            'custom_name' => $command->customName,
            'volume_factor' => $command->volumeFactor,
            'is_active' => $command->isActive,
        ]);
    }
}
