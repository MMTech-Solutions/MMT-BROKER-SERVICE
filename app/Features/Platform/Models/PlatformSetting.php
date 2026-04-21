<?php

namespace App\Features\Platform\Models;

use App\Features\Platform\Enums\PlatformEnvironment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property string $id
 * @property string $platform_id
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string $password
 * @property string $connection_id
 * @property PlatformEnvironment $environment
 * @property bool $is_active
 */
class PlatformSetting extends Model
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
            'environment' => PlatformEnvironment::class,
            'is_active' => 'boolean',
            'port' => 'integer',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
