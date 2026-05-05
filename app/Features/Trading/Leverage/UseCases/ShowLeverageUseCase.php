<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Http\V1\Commands\ShowLeverageCommand;
use App\Features\Trading\Leverage\Models\Leverage;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

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
