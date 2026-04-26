<?php

namespace App\Features\Leverage\Models;

use App\Features\TradingServer\Models\ServerGroup;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $id
 * @property string $name
 * @property int $value
 */
class Leverage extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
        ];
    }

    public function serverGroups(): BelongsToMany
    {
        return $this->belongsToMany(ServerGroup::class);
    }
}
