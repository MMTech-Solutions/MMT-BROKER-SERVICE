<?php

namespace App\Features\Manager\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class ServerGroupNotFoundException extends MmtException
{
    protected $message = 'Server group not found';

    public function __construct()
    {
        parent::__construct(
            'SERVER_GROUP_NOT_FOUND',
            'Server group not found',
        );
    }
}