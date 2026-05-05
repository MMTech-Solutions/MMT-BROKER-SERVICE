<?php

namespace App\Features\Finance\ValueObjects;

use App\SharedFeatures\ValueObjects\Number;

interface TransactionInterface
{
    public function getAmount(): Number;
}
