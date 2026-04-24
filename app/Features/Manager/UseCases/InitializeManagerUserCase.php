<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Http\V1\Commands\InitializeManagerCommand;
use App\Features\Manager\Jobs\InitializeGroupJob;

class InitializeManagerUserCase
{
    public function execute(InitializeManagerCommand $command)
    {
        InitializeGroupJob::dispatch($command->managerId);
    }
}