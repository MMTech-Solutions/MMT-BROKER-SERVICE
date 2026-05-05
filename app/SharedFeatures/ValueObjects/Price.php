<?php

namespace App\SharedFeatures\ValueObjects;

class Price
{
    public function __construct(
        private readonly PositiveNumber $value,
        private readonly Currency $currency,
    ) {}

    public function toFloat(): float
    {
        return $this->value->getValue() / pow(10, $this->currency->getPrecision());
    }
}
