<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Http\V1\Commands\SyncLeveragesCommand;
use App\Features\TradingServer\Services\SyncServerGroupLeveragesService;

class SyncLeveragesUseCase
{
    public function __construct(
        private readonly SyncServerGroupLeveragesService $syncServerGroupLeveragesService,
    ) {}

    public function execute(SyncLeveragesCommand $command): void
    {
        $this->syncServerGroupLeveragesService->execute(
            serverGroupId: $command->serverGroupId,
            leverageIds: $command->leverageIds,
        );
    }
}
