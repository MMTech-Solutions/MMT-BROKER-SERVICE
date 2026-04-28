<?php

namespace App\SharedFeatures\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class UnauthenticatedException extends MmtException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'UNAUTHENTICATED',
            'Authentication required.',
            401,
            0,
            $previous
        );
    }
}