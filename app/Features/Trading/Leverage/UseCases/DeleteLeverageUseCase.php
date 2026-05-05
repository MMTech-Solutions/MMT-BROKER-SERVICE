<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Http\V1\Commands\DeleteLeverageCommand;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

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
