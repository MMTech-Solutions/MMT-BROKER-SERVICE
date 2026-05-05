<?php

namespace App\Features\Trading\Platform\UseCases;

use App\Features\Trading\Platform\DTOs\PlatformDTO;
use App\Features\Trading\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Trading\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Trading\Platform\Http\V1\Commands\ShowPlatformCommand;
use App\Features\Trading\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class ShowPlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(ShowPlatformCommand $command): PlatformDTO
    {
        $platform = $this->platformRepository->findById($command->platformId) ?? throw new PlatformNotFoundException();

        return PlatformDTO::from($platform);
    }
}
