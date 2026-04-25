<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * 
 * @property string $id
 * @property string $name
 * @property string $alpha
 * @property int $stype
 * @property string $manager_id
 * 
 */
class Symbol extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'alpha',
        'stype',
        'manager_id',
    ];

    public function securities(): BelongsToMany
    {
        return $this->belongsToMany(Security::class);
    }
}
