<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 * @property string $id
 * @property string $name
 * @property int $position
 * @property string $manager_id
 * 
 */
class Security extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'position',
        'manager_id',
    ];

    public function symbols(): BelongsToMany
    {
        return $this->belongsToMany(Symbol::class);
    }

    public function serverGroups(): BelongsToMany
    {
        return $this->belongsToMany(ServerGroup::class);
    }
}
