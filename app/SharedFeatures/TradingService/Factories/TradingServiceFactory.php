<?php

namespace App\SharedFeatures\TradingService\Factories;

use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\TradingService;

class TradingServiceFactory
{
    public function __construct(
        private readonly TradingService $tradingServiceSdk
    ){}

    public function make(PlatformEnum $platformEnum, string $connectionId): MT5TradingServiceInterface
    {
        $session = $this->tradingServiceSdk->fromConnectionId($connectionId);
        
        return match($platformEnum)
        {
            PlatformEnum::MT5 => $session->mt5(),
            default => throw new \Exception('Platform not supported'),
        };
    }
}