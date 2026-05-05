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
        public string $externalUserId,
        public string $externalTraderId,
        public string $serverGroupId,
        public string $leverageId,
        public float $initialDeposit,
        public float $currentBalance,
        public float $currentEquity,
        public float $currentCredit,
        public float $margin,
        public float $freeMargin,
        public bool $isActive,
        public bool $isTradingEnabled,
        public ?string $comments,
    ) {}
}