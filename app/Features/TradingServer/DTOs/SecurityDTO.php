<?php

namespace App\Features\TradingServer\DTOs;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Carbon\Carbon;

#[MapOutputName(SnakeCaseMapper::class)]
class SecurityDTO extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $position,
        public readonly string $tradingServerId,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
    ) {}
}