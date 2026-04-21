<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\DeletePlatformSettingCommand;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;

class DeletePlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
        private readonly TradingServiceSessionFactory $sdkSessionFactory,
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

        $this->sdkSessionFactory->disconnect($setting->connection_id);

        $settingRepository->delete($setting);
    }
}
