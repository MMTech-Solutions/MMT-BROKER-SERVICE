<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class FindServerGroupByIdService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId): ServerGroup
    {
        return $this->serverGroupRepository->findByUuid($serverGroupId) ?? throw new ServerGroupNotFoundException();
    }
}