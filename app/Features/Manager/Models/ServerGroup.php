<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 * @property string $uuid
 * @property string $name
 * @property string $meta_name
 * @property string $platform_setting_id
 * @property Collection<Security> $securities
 * @property Collection<Symbol> $symbols
 * 
 */
class ServerGroup extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'meta_name',
        'platform_setting_id',
    ];

    public function securities(): BelongsToMany
    {
        return $this->belongsToMany(Security::class);
    }
}
