<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\StorePlatformSettingCommand;
use App\Features\Platform\Models\PlatformSetting;

class StorePlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(StorePlatformSettingCommand $command): PlatformSetting
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        return $this->platformFactory->makeSetting()->create([
            'platform_id' => $command->platformId,
            'host' => $command->host,
            'port' => $command->port,
            'username' => $command->username,
            'password' => $command->password,
            'enviroment' => $command->enviroment,
            'is_active' => $command->isActive,
        ]);
    }
}
