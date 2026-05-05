<?php

namespace App\Features\Trading\Platform\Factories;

use App\Features\Trading\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use App\Features\Trading\Platform\Repositories\PlatformRepository;

class PlatformRepositoryFactory
{
    public function make(): PlatformRepositoryInterface
    {
        return new PlatformRepository();
    }
}
