<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Http\V1\Commands\SyncLeveragesCommand;
use App\Features\Trading\TradingServer\Services\SyncServerGroupLeveragesService;

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
