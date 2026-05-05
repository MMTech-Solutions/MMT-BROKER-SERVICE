<?php

namespace App\Features\Trading\Account\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class AccountLimitExceededException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'ACCOUNT_LIMIT_EXCEEDED',
            'Account limit exceeded'
        );
    }
}