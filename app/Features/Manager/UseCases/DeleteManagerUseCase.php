<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Exceptions\ManagerNotFoundException;
use App\Features\Manager\Http\V1\Commands\DeleteManagerCommand;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;

class DeleteManagerUseCase
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
        private readonly TradingServiceSessionFactory $sdkSessionFactory,
    ) {
        $this->managerRepository = $managerRepositoryFactory->make();
    }

    public function execute(DeleteManagerCommand $command): void
    {
        $manager = $this->managerRepository->findById($command->managerId);

        if ($manager === null) {
            throw new ManagerNotFoundException();
        }

        $this->sdkSessionFactory->disconnect($manager->connection_id);

        $this->managerRepository->deleteById($manager->id);
    }
}
