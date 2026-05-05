<?php

namespace App\Features\Trading\TradingServer\DTOs;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class SymbolDTO extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $alpha,
        public readonly int $stype,
        public readonly string $tradingServerId,
    ) {}
}