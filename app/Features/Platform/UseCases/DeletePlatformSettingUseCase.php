<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\DeletePlatformSettingCommand;

class DeletePlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(DeletePlatformSettingCommand $command): void
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        $settingRepository = $this->platformFactory->makeSetting();
        $setting = $settingRepository->findById($command->settingId);

        if ($setting === null || $setting->platform_id !== $command->platformId) {
            throw new PlatformSettingNotFoundException;
        }

        $settingRepository->delete($setting);
    }
}
