<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\UpdatePlatformSettingCommand;
use App\Features\Platform\Models\PlatformSetting;

class UpdatePlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(UpdatePlatformSettingCommand $command): PlatformSetting
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

        return $settingRepository->update($setting, $command->attributes);
    }
}
