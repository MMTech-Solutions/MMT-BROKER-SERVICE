<?php

namespace App\Features\TradingServer\DTOs;

use App\Features\TradingServer\Enums\EnvironmentEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class TradingServerDTO extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $platformId,
        public readonly string $host,
        public readonly int $port,
        public readonly string $username,
        public readonly string $password,
        public readonly string $connectionId,
        public readonly EnvironmentEnum $environment,
        public readonly bool $isActive,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $initializedAt,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $createdAt,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $updatedAt,
    ) {}
}
