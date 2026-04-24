<?php

namespace App\Features\Manager\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagerSyncDoneEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        // Id de la configuración de la plataforma que se acaba de sincronizar
        public readonly string $platformSettingId
    ){}
}
