<?php

namespace App\Features\ServerGroup\Listeners;

use App\Features\Manager\Events\ManagerSyncDoneEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ManagerSyncDoneListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        
    }
    
    public function handle(ManagerSyncDoneEvent $event): void
    {
        // Notifica y/o registrar la accion
    }
}
