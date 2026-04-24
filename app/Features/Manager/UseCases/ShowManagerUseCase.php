<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Exceptions\ManagerNotFoundException;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ShowManagerCommand;
use App\Features\Manager\Models\Manager;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;

class ShowManagerUseCase
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
    ) {
        $this->managerRepository = $managerRepositoryFactory->make();
    }

    public function execute(ShowManagerCommand $command): Manager
    {
        return $this->managerRepository->findById($command->managerId) ?? throw new ManagerNotFoundException();
    }
}
