<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Http\V1\Commands\SyncTradingServerCommand;
use App\Features\TradingServer\Jobs\SyncTradingServerJob;
use App\SharedFeatures\User\User;
use App\SharedFeatures\User\UserContext;

class SyncTradingServerUseCase
{
    private User $user;

    public function __construct(
        private readonly UserContext $userContext,
    ) {
        $this->user = $userContext->userInfo();
    }

    public function execute(SyncTradingServerCommand $command)
    {
        if ($command->isAsync) {
            SyncTradingServerJob::dispatch(
                $command->tradingServerId,
                $this->user->id,
                $this->user->name,
                $this->user->email,
            );
        } else {
            SyncTradingServerJob::dispatchSync(
                $command->tradingServerId,
                $this->user->id,
                $this->user->name,
                $this->user->email,
            );
        }
    }
}
