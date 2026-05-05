<?php

namespace App\Features\Trading\TradingServer\DTOs;

use Spatie\LaravelData\Data;

class LeverageDTO extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $value,
    ) {}
}
