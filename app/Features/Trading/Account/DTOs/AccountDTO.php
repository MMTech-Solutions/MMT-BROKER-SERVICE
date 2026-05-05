<?php

namespace App\Features\Trading\Account\DTOs;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class AccountDTO extends Data
{
    public function __construct(
        public string $id,
        public string $external_user_id,
        public string $external_trader_id,
        public string $server_group_id,
        public string $leverage_id,
        public float $initial_deposit,
        public float $current_balance,
        public float $current_equity,
        public float $current_credit,
        public float $margin,
        public float $free_margin,
        public bool $is_active,
        public bool $is_trading_enabled,
        public ?string $comments,
    ) {}
}