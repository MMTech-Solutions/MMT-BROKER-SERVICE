<?php

namespace App\Features\Trading\Account\Listeners;

use App\Features\Trading\Account\Services\CreateDefaultAccountService;
use App\SharedFeatures\EventBus\Events\ExternalUserCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExternalUserCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly CreateDefaultAccountService $createDefaultAccountService,
    ) {}

    public function handle(ExternalUserCreatedEvent $event): void
    {
        $this->createDefaultAccountService->execute($event->userId);

    }
}
