<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Http\V1\Commands\StoreLeverageCommand;
use App\Features\Leverage\Models\Leverage;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

class StoreLeverageUseCase
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(StoreLeverageCommand $command): Leverage
    {
        return $this->leverageRepository->create([
            'name'  => $command->name,
            'value' => $command->value,
        ]);
    }
}
