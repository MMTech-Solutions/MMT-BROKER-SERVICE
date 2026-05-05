<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Http\V1\Commands\SyncLeveragesCommand;
use App\Features\Trading\TradingServer\Actions\SyncServerGroupLeveragesAction;

class SyncLeveragesUseCase
{
    public function __construct(
        private readonly SyncServerGroupLeveragesAction $syncServerGroupLeveragesAction,
    ) {}

    public function execute(SyncLeveragesCommand $command): void
    {
        $this->syncServerGroupLeveragesAction->execute(
            serverGroupId: $command->serverGroupId,
            leverageIds: $command->leverageIds,
        );
    }
}
