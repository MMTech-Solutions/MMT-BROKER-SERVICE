<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

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
