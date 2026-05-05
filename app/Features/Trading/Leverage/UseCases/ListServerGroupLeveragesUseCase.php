<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Http\V1\Commands\ListServerGroupLeveragesCommand;
use App\Features\Trading\TradingServer\Services\ListServerGroupLeveragesService;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupLeveragesUseCase
{
    public function __construct(
        private readonly ListServerGroupLeveragesService $listServerGroupLeveragesService,
    ) {}

    public function execute(ListServerGroupLeveragesCommand $command): LengthAwarePaginator
    {
        return $this->listServerGroupLeveragesService->execute(
            serverGroupId: $command->serverGroupId,
            filters: [
                'name'  => $command->name,
                'value' => $command->value,
            ],
            perPage: $command->perPage,
        );
    }
}
