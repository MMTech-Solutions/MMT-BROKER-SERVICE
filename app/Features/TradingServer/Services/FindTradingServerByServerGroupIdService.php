<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\DTOs\TradingServerDTO;
use App\Features\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

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

    public function execute(string $serverGroupId): TradingServerDTO
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId) ??
        throw new ServerGroupNotFoundException;

        return TradingServerDTO::from($serverGroup->tradingServer);
    }
}
