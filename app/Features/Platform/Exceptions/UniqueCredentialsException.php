<?php

namespace App\Features\Platform\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class UniqueCredentialsException extends MmtException
{
    protected $message = 'Unique credentials already exists';

    public function __construct(?string $message = null)
    {
        parent::__construct(
            'UNIQUE_CREDENTIALS_EXCEPTION',
            $message ?: $this->message
        );
    }
}