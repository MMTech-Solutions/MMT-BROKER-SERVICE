<?php

namespace App\Features\Trading\TradingServer\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class LeverageNotAssignedToServerGroupException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'LEVERAGE_NOT_ASSIGNED_TO_SERVER_GROUP',
            'The leverage is not assigned to the given server group',
        );
    }
}
