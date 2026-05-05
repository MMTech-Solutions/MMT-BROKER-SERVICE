<?php

namespace App\Features\Trading\Platform\Models;

use App\Features\Trading\Platform\Exceptions\PlatformNotSupportedException as InternalPlatformNotSupportedException;
use App\Features\Trading\TradingServer\Models\TradingServer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Exceptions\PlatformNotSupportedException;

/**
 * @property string $id
 * @property string $name
 * @property string $custom_name
 * @property float $volume_factor
 * @property bool $is_active
 */
class Platform extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'custom_name',
        'volume_factor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Converts the platform name to a PlatformEnum
     *
     * @throws InternalPlatformNotSupportedException
     */
    public function toEnum(): PlatformEnum
    {
        try {
            return PlatformEnum::tryFromString($this->name);
        } catch (PlatformNotSupportedException $e) {
            throw new InternalPlatformNotSupportedException($e->getMessage());
        }
    }

    public function tradingServers(): HasMany
    {
        return $this->hasMany(TradingServer::class);
    }
}
