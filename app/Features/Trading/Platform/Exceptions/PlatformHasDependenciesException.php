<?php

namespace App\Features\Trading\Platform\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class PlatformHasDependenciesException extends MmtException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'PLATFORM_HAS_DEPENDENCIES',
            'Platform cannot be deleted because it is referenced by other records.',
            409,
            0,
            $previous
        );
    }
}
