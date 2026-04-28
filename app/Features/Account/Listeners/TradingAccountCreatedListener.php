<?php

namespace App\Features\Account\Listeners;

use App\Features\Account\Events\TradingAccountCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TradingAccountCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }
    
    public function handle(TradingAccountCreatedEvent $event): void
    {
        
    }
}
