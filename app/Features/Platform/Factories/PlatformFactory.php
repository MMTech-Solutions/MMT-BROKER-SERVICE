<?php

namespace App\Features\Platform\Factories;

use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use App\Features\Platform\Repositories\PlatformRepository;

class PlatformFactory
{
    public function make(): PlatformRepositoryInterface
    {
        return new PlatformRepository;
    }
}
