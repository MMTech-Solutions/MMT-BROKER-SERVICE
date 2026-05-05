<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\DTOs\PlatformDTO;
use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Http\V1\Commands\ShowPlatformCommand;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;

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
