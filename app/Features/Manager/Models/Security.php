<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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

    public function symbols()
    {
        return $this->belongsToMany(Symbol::class);
    }
}
