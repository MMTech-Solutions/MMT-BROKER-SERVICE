<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\DTOs\ServerGroupDTO;
use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

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
