<?php

namespace App\Features\Trading\TradingServer\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class TradingServerNotFoundException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'TradingServer_NOT_FOUND',
            'TradingServer not found'
        );
    }
}