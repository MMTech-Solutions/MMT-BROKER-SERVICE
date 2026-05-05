<?php

namespace App\Features\TradingServer\DTOs;

use App\SharedFeatures\ValueObjects\PositiveNumber;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

class InitialAmountDTO extends Data
{
    public function __construct(
        public readonly string $id,

        #[WithCastable(PositiveNumber::class)]
        public readonly PositiveNumber $amount,
    ) {}
}
