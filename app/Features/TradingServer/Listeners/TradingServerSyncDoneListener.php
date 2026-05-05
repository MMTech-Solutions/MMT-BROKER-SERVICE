<?php

namespace App\Features\TradingServer\Listeners;

use App\Features\TradingServer\Events\TradingServerSyncDoneEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TradingServerSyncDoneListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        
    }
    
    public function handle(TradingServerSyncDoneEvent $event): void
    {
        // Notifica y/o registrar la accion
    }
}
