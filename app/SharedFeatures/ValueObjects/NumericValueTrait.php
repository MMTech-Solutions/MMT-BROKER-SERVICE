<?php

namespace App\SharedFeatures\ValueObjects;

trait NumericValueTrait
{
    public static function fromInt(int $value): self
    {
        return new static($value);
    }

    public static function fromFloat(float $value): self
    {
        return new static($value);
    }

    public function getValue(): int|float
    {
        return $this->value;
    }

    public function isGreaterThanZero(): bool
    {
        return $this->value > 0;
    }

    public static function zero(): self
    {
        return new static(0);
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function isNegative(): bool
    {
        return $this->value < 0;
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function isPositiveOrZero(): bool
    {
        return $this->value >= 0;
    }

    public function isNegativeOrZero(): bool
    {
        return $this->value <= 0;
    }

    public function isInteger(): bool
    {
        return is_int($this->value);
    }

    public function isFloat(): bool
    {
        return is_float($this->value);
    }

    public function isGreaterThan(NumericValueInterface $other): bool
    {
        return $this->getValue() > $other->getValue();
    }

    public function isGreaterThanOrEqualTo(NumericValueInterface $other): bool
    {
        return $this->getValue() >= $other->getValue();
    }

    public function isLessThan(NumericValueInterface $other): bool
    {
        return $this->getValue() < $other->getValue();
    }

    public function isLessThanOrEqualTo(NumericValueInterface $other): bool
    {
        return $this->getValue() <= $other->getValue();
    }

    public function isEqualTo(NumericValueInterface $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function add(NumericValueInterface $other): NumericValueInterface
    {
        return new static(
            $this->getValue() + $other->getValue()
        );
    }

    public function subtract(NumericValueInterface $other): NumericValueInterface
    {
        return new static(
            $this->getValue() - $other->getValue()
        );
    }

    public function multiply(NumericValueInterface $other): NumericValueInterface
    {
        return new static(
            bcmul($this->getValue(), $other->getValue())
        );
    }

    public function divide(NumericValueInterface $other): NumericValueInterface
    {
        return new static(
            bcdiv($this->getValue(), $other->getValue())
        );
    }
}