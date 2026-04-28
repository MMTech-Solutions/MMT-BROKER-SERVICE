<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\TradingServer\Models\TradingServer;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;

class FindTradingServerByServerGroupIdService
{
    protected TradingServerRepositoryInterface $tradingServerRepository;
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $tradingServerRepositoryFactory,
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->tradingServerRepository = $tradingServerRepositoryFactory->make();
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId): TradingServer
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId) ??
        throw new ServerGroupNotFoundException();
        
        return $serverGroup->tradingServer;
    }
}