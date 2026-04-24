<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Http\V1\Commands\SyncManagerCommand;
use App\Features\Manager\Jobs\SyncManagerJob;

class SyncManagerUseCase
{
    public function execute(SyncManagerCommand $command)
    {
        SyncManagerJob::dispatch($command->managerId);
    }
}