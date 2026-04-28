<?php

namespace App\SharedFeatures\TradingService\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;
use Throwable;

class TradingServiceException extends MmtException
{
    public function __construct(string $message, int $httpCode = 400, ?Throwable $previous = null)
    {
        parent::__construct(
            'TRADING_SERVICE_ERROR',
            $message,
            $httpCode,
            0,
            $previous
        );
    }
}
