<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

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
