<?php

namespace App\Features\Trading\TradingServer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradingServerSyncDoneEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        // Id de la configuración de la plataforma que se acaba de sincronizar
        public readonly string $platformSettingId,
        public readonly string $userId,
        public readonly string $userName,
        public readonly string $userEmail,
    ) {}
}
