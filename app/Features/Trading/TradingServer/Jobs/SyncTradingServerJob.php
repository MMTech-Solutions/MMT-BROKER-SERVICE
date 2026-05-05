<?php

namespace App\Features\Trading\TradingServer\Jobs;

use App\Features\Trading\TradingServer\Actions\SyncTradingServerAction;
use App\Features\Trading\TradingServer\Events\TradingServerSyncDoneEvent;
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
        SyncTradingServerAction $syncTradingServerAction,
    ): void {
        $syncTradingServerAction->execute($this->tradingServerId);
        TradingServerSyncDoneEvent::dispatch(
            $this->tradingServerId,
            $this->userId,
            $this->userName,
            $this->userEmail,
        );
    }
}
