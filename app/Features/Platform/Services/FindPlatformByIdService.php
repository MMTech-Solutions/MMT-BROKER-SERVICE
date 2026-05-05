<?php

namespace App\Features\Platform\Services;

use App\Features\Platform\DTOs\PlatformDTO;
use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class FindPlatformByIdService
{
    protected PlatformRepositoryInterface $platformRepository;

    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(string $platformId): PlatformDTO
    {
        $platform = $this->platformRepository->findById($platformId) ?? throw new PlatformNotFoundException;

        return PlatformDTO::from($platform);
    }
}
