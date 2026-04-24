<?php

namespace App\Features\Manager\Exceptions;

use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class ManagerNotFoundException extends MmtException
{
    public function __construct()
    {
        parent::__construct(
            'MANAGER_NOT_FOUND',
            'Manager not found'
        );
    }
}