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
        private readonly string $tradingServerId,
        private readonly string $userId,
        private readonly string $userName,
        private readonly string $userEmail,
    ) {}

    public function handle(
        SyncTradingServerService $syncTradingServerService,
    ): void {
        $syncTradingServerService->execute($this->tradingServerId);
        TradingServerSyncDoneEvent::dispatch(
            $this->tradingServerId,
            $this->userId,
            $this->userName,
            $this->userEmail,
        );
    }
}
