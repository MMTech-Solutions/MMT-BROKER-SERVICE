<?php

namespace App\Features\Platform\Http\V1\Resources;

use App\Features\Platform\Models\Platform;
use App\SharedFeatures\Application\UserContext;
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
        $userContext = app(UserContext::class);
        $isAdmin = $userContext->isAdmin();
        
        return [
            'id' => $this->id,
            'name' => $isAdmin ? $this->name : ($this->custom_name ?? $this->name),
            'custom_name' => $this->when($isAdmin, $this->custom_name),
            'volume_factor' => $this->volume_factor,
            'is_active' => $this->when($isAdmin, $this->is_active),
            'created_at' => $this->when($isAdmin, $this->created_at?->toIso8601String()),
            'updated_at' => $this->when($isAdmin, $this->updated_at?->toIso8601String()),
        ];
    }
}
