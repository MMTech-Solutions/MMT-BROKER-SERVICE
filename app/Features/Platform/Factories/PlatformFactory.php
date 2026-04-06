<?php

namespace App\Features\Platform\Factories;

use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use App\Features\Platform\Repositories\Contracts\PlatformSettingRepositoryInterface;
use App\Features\Platform\Repositories\PlatformRepository;
use App\Features\Platform\Repositories\PlatformSettingRepository;

class PlatformFactory
{
    public function make(): PlatformRepositoryInterface
    {
        return new PlatformRepository;
    }

    public function makeSetting(): PlatformSettingRepositoryInterface
    {
        return new PlatformSettingRepository;
    }
}
