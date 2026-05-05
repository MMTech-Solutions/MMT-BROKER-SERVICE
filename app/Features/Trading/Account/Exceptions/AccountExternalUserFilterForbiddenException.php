<?php

namespace App\Features\Trading\Account\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class AccountExternalUserFilterForbiddenException extends MmtException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'ACCOUNT_EXTERNAL_USER_FILTER_FORBIDDEN',
            'You are not allowed to filter accounts by external_user_id.',
            403,
            0,
            $previous
        );
    }
}
