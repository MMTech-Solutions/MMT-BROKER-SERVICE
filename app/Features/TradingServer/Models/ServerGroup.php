<?php

namespace App\Features\TradingServer\Models;

use App\Features\Leverage\Models\Leverage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mmt\TradingServiceSdk\Platforms\MT5\Enums\TransactionTypeEnum;

/**
 * @property string $id
 * @property string $trading_server_id
 * @property string $name
 * @property string $meta_name
 * @property string $description
 * @property array $currency
 * @property int $account_limits
 * @property int $min_deposit
 * @property int $min_withdrawal
 * @property int $default_amount
 * @property TransactionTypeEnum $default_amount_type
 * @property int $currency_denomination_factor
 * @property bool $is_private
 * @property bool $is_default
 * @property bool $is_active
 * @property bool $is_deposit_enabled
 * @property bool $is_withdrawal_enabled
 * @property bool $use_countries_restrictions
 * @property array $restricted_countries
 * @property Collection<Security> $securities
 * @property Collection<Symbol> $symbols
 * @property Collection<Leverage> $leverages
 * @property TradingServer $tradingServer
 */
class ServerGroup extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'meta_name',
        'description',
        'currency',
        'trading_server_id',
        'account_limits',
        'min_deposit',
        'min_withdrawal',
        'default_amount',
        'default_amount_type',
        'currency_denomination_factor',
        'is_private',
        'is_default',
        'is_active',
        'is_deposit_enabled',
        'is_withdrawal_enabled',
        'use_countries_restrictions',
        'restricted_countries',
    ];

    protected function casts(): array
    {
        return [
            'currency' => 'array',
            'restricted_countries' => 'array',
            'default_amount_type' => TransactionTypeEnum::class,
        ];
    }

    public function securities(): BelongsToMany
    {
        return $this->belongsToMany(Security::class);
    }

    public function leverages(): BelongsToMany
    {
        return $this->belongsToMany(Leverage::class);
    }

    public function tradingServer(): BelongsTo
    {
        return $this->belongsTo(TradingServer::class);
    }

    public function initialAmounts(): BelongsToMany
    {
        return $this->belongsToMany(InitialAmount::class);
    }
}
