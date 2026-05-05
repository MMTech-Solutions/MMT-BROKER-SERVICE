<?php

namespace App\SharedFeatures\ValueObjects;

class PositiveMoney
{
    public function __construct(
        private readonly PositiveNumber $amount,
        private readonly Currency $currency,
    ) {}

    public function toFloat(): float
    {
        return $this->amount->getValue() / pow(10, $this->currency->getPrecision());
    }

    public function toCents(): int
    {
        return (int)$this->amount->getValue();
    }
}
