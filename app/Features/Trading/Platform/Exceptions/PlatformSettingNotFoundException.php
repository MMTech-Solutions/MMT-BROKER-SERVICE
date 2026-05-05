<?php

namespace App\Features\Trading\Platform\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class PlatformSettingNotFoundException extends MmtException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'PLATFORM_SETTING_NOT_FOUND',
            'Platform setting not found.',
            404,
            0,
            $previous
        );
    }
}
