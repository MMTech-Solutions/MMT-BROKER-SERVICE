<?php

namespace App\Features\TradingServer\Models;

use App\Features\Platform\Models\Platform;
use App\Features\TradingServer\Enums\EnvironmentEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $platform_id
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string $password
 * @property string $connection_id
 * @property EnvironmentEnum $environment
 * @property bool $is_active
 * @property Carbon|null $initialized_at
 * @property Platform $platform
 * @property ServerGroup $serverGroup
 */
class TradingServer extends Model
{
    use HasUuids;

    protected $fillable = [
        'platform_id',
        'host',
        'port',
        'username',
        'password',
        'connection_id',
        'environment',
        'is_active',
        'initialized_at',
    ];

    protected function casts(): array
    {
        return [
            'environment' => EnvironmentEnum::class,
            'is_active' => 'boolean',
            'port' => 'integer',
            'initialized_at' => 'datetime',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function serverGroups(): HasMany
    {
        return $this->hasMany(ServerGroup::class);
    }
}
