<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Http\V1\Commands\StoreLeverageCommand;
use App\Features\Trading\Leverage\Models\Leverage;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

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
