<?php

namespace App\SharedFeatures\ValueObjects;

use Illuminate\Support\Facades\Validator;

class Currency
{
    public function __construct(
        private readonly string $code,
        private readonly int $precision,
    ) {
        Validator::validate(['code' => $this->code, 'precision' => $this->precision], [
            'code' => 'required|string|max:3',
            'precision' => 'required|integer|min:1',
        ]);
    }

    public static function fallback(): self
    {
        return new self('USD', 2);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }
}
