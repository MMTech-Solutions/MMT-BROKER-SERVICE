<?php

namespace App\Features\TradingServer\Services;

use App\Features\Leverage\Models\Leverage;
use App\Features\TradingServer\Exceptions\LeverageNotAssignedToServerGroupException;
use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class FindLeverageForServerGroupService
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId, string $leverageId): Leverage
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId)
            ?? throw new ServerGroupNotFoundException();

        /** @var Leverage|null $leverage */
        $leverage = $serverGroup->leverages()
            ->where('leverages.id', $leverageId)
            ->first();

        if ($leverage === null) {
            throw new LeverageNotAssignedToServerGroupException();
        }

        return $leverage;
    }
}
