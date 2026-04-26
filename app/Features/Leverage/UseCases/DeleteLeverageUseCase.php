<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Http\V1\Commands\DeleteLeverageCommand;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

class DeleteLeverageUseCase
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(DeleteLeverageCommand $command): void
    {
        $leverage = $this->leverageRepository->findById($command->leverageId)
            ?? throw new LeverageNotFoundException();

        $this->leverageRepository->deleteById($leverage->id);
    }
}
