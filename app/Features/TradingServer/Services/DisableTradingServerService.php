<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

class DisableTradingServerService
{
    protected TradingServerRepositoryInterface $tradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $tradingServerRepositoryFactory,
    ) {
        $this->tradingServerRepository = $tradingServerRepositoryFactory->make();
    }

    public function execute(string $tradingServerId): void {}
}
