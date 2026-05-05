<?php

namespace App\Features\Trading\Platform\Services;

use App\Features\Trading\Platform\DTOs\PlatformDTO;
use App\Features\Trading\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Trading\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Trading\Platform\Repositories\Contracts\PlatformRepositoryInterface;

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
