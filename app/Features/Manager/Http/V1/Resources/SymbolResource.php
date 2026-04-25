<?php

namespace App\Features\Manager\Http\V1\Resources;

use App\Features\Manager\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Symbol
 */
class SymbolResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'alpha' => $this->alpha,
            'stype' => $this->stype,
            'manager_id' => $this->manager_id,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
