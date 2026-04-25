<?php

namespace App\Features\TradingServer\Models;

use App\Features\TradingServer\Enums\EnvironmentEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Features\Platform\Models\Platform;


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
 * 
 * @property Platform $platform
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
    ];

    protected function casts(): array
    {
        return [
            'environment' => EnvironmentEnum::class,
            'is_active' => 'boolean',
            'port' => 'integer',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
