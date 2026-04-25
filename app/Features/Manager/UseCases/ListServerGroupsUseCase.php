<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\ServerGroupRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ListServerGroupsCommand;
use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupsUseCase
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(ListServerGroupsCommand $command): LengthAwarePaginator
    {
        return $this->serverGroupRepository->paginate(
            filters: [
                'manager_id' => $command->managerId,
                'name' => $command->name,
                'meta_name' => $command->metaName,
            ],
            perPage: $command->perPage,
        );
    }
}
