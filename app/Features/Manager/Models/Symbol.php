<?php

namespace App\Features\Manager\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'alpha',
        'stype',
        'platform_setting_id',
    ];
}
