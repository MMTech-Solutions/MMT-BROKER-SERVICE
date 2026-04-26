<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Http\V1\Commands\ShowLeverageCommand;
use App\Features\Leverage\Models\Leverage;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

class ShowLeverageUseCase
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(ShowLeverageCommand $command): Leverage
    {
        return $this->leverageRepository->findById($command->leverageId)
            ?? throw new LeverageNotFoundException();
    }
}
