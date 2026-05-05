<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\DTOs\ServerGroupDTO;
use App\Features\Trading\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class FindServerGroupByIdService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId): ServerGroupDTO
    {
        $group = $this->serverGroupRepository->findByUuid($serverGroupId) ?? throw new ServerGroupNotFoundException;

        return ServerGroupDTO::from($group);
    }
}
