<?php

namespace App\Features\Platform\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'custom_name',
        'code',
        'volume_factor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
