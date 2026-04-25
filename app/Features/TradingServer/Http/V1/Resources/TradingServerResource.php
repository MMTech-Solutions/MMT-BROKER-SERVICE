<?php

namespace App\Features\TradingServer\Http\V1\Resources;

use App\Features\TradingServer\Models\TradingServer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TradingServer
 */
class TradingServerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'platform_id' => $this->platform_id,
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'connection_id' => $this->connection_id,
            'environment' => [
                'value' => $this->environment->value,
                'label' => $this->environment->name,
            ],
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
