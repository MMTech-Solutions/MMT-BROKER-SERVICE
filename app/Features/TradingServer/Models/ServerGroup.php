<?php

namespace App\Features\TradingServer\Models;

use App\Features\Leverage\Models\Leverage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * @property string $id
 * @property string $name
 * @property string $meta_name
 * @property string $trading_server_id
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
        'trading_server_id',
    ];

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
}
