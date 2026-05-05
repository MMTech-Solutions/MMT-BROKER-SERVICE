<?php

namespace App\SharedFeatures\ValueObjects;

use Illuminate\Support\Facades\Validator;

class Number implements NumericValueInterface
{
    use NumericValueTrait;

    public function __construct(
        private readonly int|float $value,
    ) {
        Validator::validate(['value' => $this->value], [
            'value' => 'required|numeric',
        ], [
            'value.required' => 'The value is required',
            'value.numeric' => 'The value must be a number',
        ]);
    }

    public function getValue(): int|float
    {
        return $this->value;
    }
}
