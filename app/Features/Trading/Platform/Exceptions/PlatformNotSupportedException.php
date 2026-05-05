<?php

namespace App\Features\Trading\Platform\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class PlatformNotSupportedException extends MmtException
{
    protected $message = 'Platform not supported';

    public function __construct(string $message = '', int $httpStatusCode = 400, ?\Throwable $previous = null)
    {
        parent::__construct(
            'PLATFORM_NOT_SUPPORTED',
            $message ?: $this->message,
            $httpStatusCode,
            0,
            $previous
        );
    }
}