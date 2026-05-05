<?php

namespace App\Features\Trading\TradingServer\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class InitialAmountNotFoundException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'INITIAL_AMOUNT_NOT_FOUND',
            'Initial amount not found'
        );
    }
}
