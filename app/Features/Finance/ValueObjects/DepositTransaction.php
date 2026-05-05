<?php

namespace App\Features\Finance\ValueObjects;

use App\SharedFeatures\ValueObjects\Currency;
use App\SharedFeatures\ValueObjects\Number;
use Illuminate\Support\Facades\Validator;

class DepositTransaction implements TransactionInterface
{
    public function __construct(
        private readonly Number $amount,
        private readonly Currency $currency,
    ) {
        Validator::validate([
            'amount' => $this->amount->getValue(),
        ], [
            'amount' => 'required|numeric|gt:0',
        ]);
    }

    public function getAmount(): Number
    {
        return $this->amount;
    }
}
