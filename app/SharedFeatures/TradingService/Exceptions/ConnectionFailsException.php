<?php

namespace App\SharedFeatures\TradingService\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class ConnectionFailsException extends MmtException
{
    protected $message = 'Trading service connection failed';

    public function __construct(string $message = '', int $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct(
            'TRADING_SERVICE_CONNECTION_FAILED',
            $message ?: $this->message,
            $code,
            0,
            $previous
        );
    }
}