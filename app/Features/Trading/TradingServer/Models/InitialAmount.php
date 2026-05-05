<?php

namespace App\Features\Trading\TradingServer\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property int $amount
 */
class InitialAmount extends Model
{
    use HasUuids;

    protected $fillable = [
        'amount',
    ];
}
