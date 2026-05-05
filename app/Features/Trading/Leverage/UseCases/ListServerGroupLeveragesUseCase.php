<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Http\V1\Commands\ListServerGroupLeveragesCommand;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupLeveragesUseCase
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(ListServerGroupLeveragesCommand $command): LengthAwarePaginator
    {
        return $this->serverGroupRepository->paginateLeveragesForServerGroup(
            $command->serverGroupId,
            [
                'name' => $command->name,
                'value' => $command->value,
            ],
            $command->perPage,
        );
    }
}
