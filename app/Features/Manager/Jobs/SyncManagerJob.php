<?php

namespace App\Features\Manager\Jobs;

use App\Features\Manager\Events\ManagerSyncDoneEvent;
use App\Features\Manager\Services\SyncManagerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncManagerJob implements ShouldQueue
{
    use Queueable;
    
    public function __construct(
        private readonly string $managerId,
    ){}

    public function handle(
        SyncManagerService $syncManagerService,
    ): void
    {
        $syncManagerService->execute($this->managerId);
        ManagerSyncDoneEvent::dispatch($this->managerId);
    }
}
