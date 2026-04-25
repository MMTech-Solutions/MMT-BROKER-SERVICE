<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\SecurityRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ListSecuritiesCommand;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSecuritiesUseCase
{
    private SecurityRepositoryInterface $securityRepository;

    public function __construct(
        SecurityRepositoryFactory $securityRepositoryFactory,
    ) {
        $this->securityRepository = $securityRepositoryFactory->make();
    }

    public function execute(ListSecuritiesCommand $command): LengthAwarePaginator
    {
        return $this->securityRepository->paginate(
            filters: [
                'manager_id' => $command->managerId,
                'name' => $command->name,
            ],
            perPage: $command->perPage,
        );
    }
}
