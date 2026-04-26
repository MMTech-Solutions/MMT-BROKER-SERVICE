<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class SyncServerGroupLeveragesService
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId, array $leverageIds): void
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId)
            ?? throw new ServerGroupNotFoundException();

        $serverGroup->leverages()->sync($leverageIds);
    }
}
