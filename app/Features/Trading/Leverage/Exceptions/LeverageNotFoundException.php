<?php

namespace App\Features\Trading\Leverage\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class LeverageNotFoundException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'LEVERAGE_NOT_FOUND',
            'Leverage not found'
        );
    }
}
