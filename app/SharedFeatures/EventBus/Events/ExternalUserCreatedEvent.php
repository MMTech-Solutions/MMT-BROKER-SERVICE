<?php

namespace App\SharedFeatures\EventBus\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExternalUserCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $externalUserId
    ) {}
}
