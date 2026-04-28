<?php

namespace App\Features\Account\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 * @property string $id
 * @property string $external_user_id
 * @property string $external_trader_id
 * @property string $password
 * @property string $investor_password
 * @property string $server_group_id
 * @property string $leverage_id
 * @property float $initial_deposit
 * @property float $current_balance
 * @property float $current_equity
 * 
 */
class Account extends Model
{
    use HasUuids;

    protected $fillable = [
        'external_user_id',
        'external_trader_id',
        'password',
        'investor_password',
        'server_group_id',
        'leverage_id',
        'initial_deposit',
        'current_balance',
        'current_equity',
        'current_credit',
        'margin',
        'free_margin',
        'is_active',
        'is_trading_enabled',
        'comments',
    ];
}
