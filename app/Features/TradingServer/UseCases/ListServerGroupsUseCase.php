<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\DTOs\ServerGroupDTO;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\ListServerGroupsCommand;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use Spatie\LaravelData\PaginatedDataCollection;

class ListServerGroupsUseCase
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(ListServerGroupsCommand $command): PaginatedDataCollection
    {
        $serverGroups = $this->serverGroupRepository->paginate(
            filters: [
                'trading_server_id' => $command->TradingServerId,
                'name' => $command->name,
                'meta_name' => $command->metaName,
            ],
            perPage: $command->perPage,
        );

        return ServerGroupDTO::collect(
            $serverGroups,
            PaginatedDataCollection::class
        );
    }
}
