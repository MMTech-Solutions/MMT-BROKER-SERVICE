<?php

namespace App\Features\Finance\ValueObjects;

use Illuminate\Support\Facades\Validator;

class WithdrawalTransaction implements TransactionInterface
{
    public function __construct(
        private readonly Number $amount,
        private readonly string $currency,
    ) {
        Validator::validate([
            'amount' => $this->amount->getValue(),
            'currency' => $this->currency,
        ], [
            'amount' => 'required|numeric|lt:0',
            'currency' => 'required|string|max:3',
        ]);
    }

    public function getAmount(): Number
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
