<?php

namespace App\Features\TradingServer\Jobs;

use App\Features\TradingServer\Events\TradingServerSyncDoneEvent;
use App\Features\TradingServer\Services\SyncTradingServerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncTradingServerJob implements ShouldQueue
{
    use Queueable;
    
    public function __construct(
        private readonly string $TradingServerId,
    ){}

    public function handle(
        SyncTradingServerService $syncTradingServerService,
    ): void
    {
        $syncTradingServerService->execute($this->TradingServerId);
        TradingServerSyncDoneEvent::dispatch($this->TradingServerId);
    }
}
