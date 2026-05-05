<?php

namespace App\Features\Trading\Account\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class InvalidAccountOperationException extends MmtException
{
    protected $message = 'Invalid account operation';

    public function __construct(string $message = '')
    {
        parent::__construct(
            'INVALID_ACCOUNT_OPERATION',
            $message ?: $this->message,
        );
    }
}
