<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ListManagersCommand;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListManagersUseCase
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        ManagerRepositoryFactory $managerRepositoryFactory,
    ) {
        $this->managerRepository = $managerRepositoryFactory->make();
    }

    public function execute(ListManagersCommand $command): LengthAwarePaginator
    {
        return $this->managerRepository->paginate(
            filters: [
                'host' => $command->host,
                'username' => $command->username,
                'port' => $command->port,
                'enviroment' => $command->enviroment,
                'is_active' => $command->isActive,
                'platform_id' => $command->platformId,
            ],
            perPage: $command->perPage,
        );
    }
}
