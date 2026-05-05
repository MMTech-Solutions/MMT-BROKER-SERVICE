<?php

namespace App\Features\Account\DTOs;

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
        public string $password,
        public string $investorPassword,
    ) {}
}