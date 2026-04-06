<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\ShowPlatformSettingCommand;
use App\Features\Platform\Models\PlatformSetting;

class ShowPlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(ShowPlatformSettingCommand $command): PlatformSetting
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        $setting = $this->platformFactory->makeSetting()->findById($command->settingId);

        if ($setting === null || $setting->platform_id !== $command->platformId) {
            throw new PlatformSettingNotFoundException;
        }

        return $setting;
    }
}
