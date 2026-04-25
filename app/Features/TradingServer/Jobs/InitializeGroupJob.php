<?php

namespace App\Features\TradingServer\Jobs;

use App\Features\TradingServer\Events\TradingServerSyncDoneEvent;
use App\Features\TradingServer\Services\InitializeTradingServerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class InitializeGroupJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $TradingServerId
    ){}

    public function handle(
        InitializeTradingServerService $initializeTradingServerService,
    ): void
    {
        $initializeTradingServerService->execute($this->TradingServerId);
        TradingServerSyncDoneEvent::dispatch($this->TradingServerId);
    }
}
