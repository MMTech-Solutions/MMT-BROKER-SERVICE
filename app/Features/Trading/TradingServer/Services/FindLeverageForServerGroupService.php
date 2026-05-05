<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\Leverage\Models\Leverage;
use App\Features\Trading\Leverage\DTOs\LeverageDTO;
use App\Features\Trading\TradingServer\Exceptions\LeverageNotAssignedToServerGroupException;
use App\Features\Trading\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class FindLeverageForServerGroupService
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    /**
     * @throws ServerGroupNotFoundException
     * @throws LeverageNotAssignedToServerGroupException
     */
    public function execute(string $serverGroupId, string $leverageId): LeverageDTO
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId)
            ?? throw new ServerGroupNotFoundException;

        /** @var Leverage|null $leverage */
        $leverage = $serverGroup->leverages()
            ->where('leverages.id', $leverageId)
            ->first();

        if ($leverage === null) {
            throw new LeverageNotAssignedToServerGroupException;
        }

        return LeverageDTO::from($leverage);
    }
}
