<?php

namespace App\Features\Trading\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradingAccountCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $id,
        public readonly string $externalUserId,
        public readonly string $externalTraderId,
        public readonly string $password,
        public readonly string $investorPassword,
        public readonly string $userFullName,
        public readonly string $userEmail,
    )
    {}
}
