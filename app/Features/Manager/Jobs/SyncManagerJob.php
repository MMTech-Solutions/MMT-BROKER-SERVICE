<?php

namespace App\Features\Manager\Jobs;

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
        // Sincronizar los grupos de instrumentos (securities) y los instrumentos (symbols)
        $syncManagerService->execute($this->managerId);
    }
}
