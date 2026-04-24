<?php

namespace App\Features\Manager\Http\V1\Commands;

class SyncManagerCommand
{
    public function __construct(
        public string $managerId,
    ) {}
}