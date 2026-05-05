<?php

namespace App\SharedFeatures\ValueObjects;

class Money
{
    public function __construct(
        private readonly Number $value,
        private readonly Currency $currency,
    ) {}

    public function getValue(): Number
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return $this->value->getValue() / pow(10, $this->currency->getPrecision());
    }
}
