<?php

namespace App\SharedFeatures\TradingService\Factories;

use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;
use Mmt\TradingServiceSdk\Platforms\TradingService;
use Mmt\TradingServiceSdk\Session\BrokerSessionInterface;

class TradingServiceSessionFactory
{
    public function __construct(
        private readonly TradingService $tradingServiceSdk
    ){}

    public function make(ConnectBrokerCommand $command, ?string &$connectionId = null): BrokerSessionInterface
    {
        return $this->tradingServiceSdk->connect($command, $connectionId);
    }

    public function disconnect(string $connectionId): void
    {
        $this->tradingServiceSdk->disconnect($connectionId);
    }
}