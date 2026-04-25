<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\SecurityRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ListServerGroupSecuritiesCommand;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupSecuritiesUseCase
{
    private SecurityRepositoryInterface $securityRepository;

    public function __construct(
        SecurityRepositoryFactory $securityRepositoryFactory,
    ) {
        $this->securityRepository = $securityRepositoryFactory->make();
    }

    public function execute(ListServerGroupSecuritiesCommand $command): LengthAwarePaginator
    {
        return $this->securityRepository->paginate(
            filters: [
                'manager_id' => $command->managerId,
                'server_group_id' => $command->serverGroupId,
                'name' => $command->name,
            ],
            perPage: $command->perPage,
        );
    }
}
