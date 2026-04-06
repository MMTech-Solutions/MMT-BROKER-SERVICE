<?php

namespace App\Features\Platform\Models;

use App\Features\Platform\Enums\PlatformEnviroment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformSetting extends Model
{
    use HasUuids;

    protected $fillable = [
        'platform_id',
        'host',
        'port',
        'username',
        'password',
        'enviroment',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'enviroment' => PlatformEnviroment::class,
            'is_active' => 'boolean',
            'port' => 'integer',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
