<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\DTOs\PlatformDTO;
use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Http\V1\Commands\UpdatePlatformCommand;
use App\Features\Platform\QueryObjects\DisablePlatformTreeQueryObject;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;

class UpdatePlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;

    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
        private readonly DisablePlatformTreeQueryObject $disablePlatformTreeQueryObject,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(UpdatePlatformCommand $command): PlatformDTO
    {
        $platform = $this->platformRepository->findById($command->platformId)
            ?? throw new PlatformNotFoundException();

        if (isset($command->attributes['is_active']) && $command->attributes['is_active'] === false) {
            $this->disablePlatformTreeQueryObject->execute($platform->id);
        }

        return PlatformDTO::from($this->platformRepository->update($platform, $command->attributes));
    }
}
