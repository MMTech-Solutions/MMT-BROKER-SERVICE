<?php

namespace App\Features\Platform\Http\V1\Resources;

use App\Features\Platform\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Platform
 */
class PlatformResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'custom_name' => $this->custom_name,
            'volume_factor' => $this->volume_factor,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
