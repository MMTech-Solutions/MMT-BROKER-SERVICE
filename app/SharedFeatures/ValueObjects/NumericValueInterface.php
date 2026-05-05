<?php

namespace App\SharedFeatures\ValueObjects;

interface NumericValueInterface
{
    public static function fromInt(int $value): self;
    public static function fromFloat(float $value): self;
    public function getValue(): int|float;
    public function isPositive(): bool;
    public function isNegative(): bool;
    public function isZero(): bool;
    public function isPositiveOrZero(): bool;
    public function isNegativeOrZero(): bool;
    public function isInteger(): bool;
    public function isFloat(): bool;
    public function isGreaterThan(self $other): bool;
    public function isGreaterThanOrEqualTo(self $other): bool;
    public function isLessThan(self $other): bool;
    public function isLessThanOrEqualTo(self $other): bool;
    public function isEqualTo(self $other): bool;
    public function add(self $other): self;
    public function subtract(self $other): self;
    public function multiply(self $other): self;
    public function divide(self $other): self;
}