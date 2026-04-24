<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'position',
        'platform_setting_id',
    ];

    public function symbols()
    {
        return $this->belongsToMany(Symbol::class);
    }
}
