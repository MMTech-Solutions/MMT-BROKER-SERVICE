<?php

namespace App\Features\Platform\Services;

use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Repositories\Contracts\PlatformSettingRepositoryInterface;
use App\Features\Platform\Models\PlatformSetting;

class FindPlatformServerSettingService
{
    protected PlatformSettingRepositoryInterface $platformSettingRepository;
    
    public function __construct(
        private readonly PlatformFactory $platformFactory
    ) {
        $this->platformSettingRepository = $this->platformFactory->makeSetting();
    }

    public function execute(string $platformSettingId): PlatformSetting
    {
        return $this->platformSettingRepository->findById($platformSettingId) ?? throw new PlatformSettingNotFoundException();
    }
}