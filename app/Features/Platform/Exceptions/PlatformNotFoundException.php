<?php

namespace App\Features\Platform\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class PlatformNotFoundException extends MmtException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'PLATFORM_NOT_FOUND',
            'Platform not found.',
            404,
            0,
            $previous
        );
    }
}
