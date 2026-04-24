<?php

namespace App\Features\Manager\Jobs;

use App\Features\Manager\Events\ManagerSyncDoneEvent;
use App\Features\Manager\Services\InitializeManagerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class InitializeGroupJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $managerId
    ){}

    public function handle(
        InitializeManagerService $initializeManagerService,
    ): void
    {
        $initializeManagerService->execute($this->managerId);
        ManagerSyncDoneEvent::dispatch($this->managerId);
    }
}
