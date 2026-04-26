<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Http\V1\Commands\UpdateLeverageCommand;
use App\Features\Leverage\Models\Leverage;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

class UpdateLeverageUseCase
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(UpdateLeverageCommand $command): Leverage
    {
        $leverage = $this->leverageRepository->findById($command->leverageId)
            ?? throw new LeverageNotFoundException();

        $attributes = array_filter([
            'name'  => $command->name,
            'value' => $command->value,
        ], fn ($v) => $v !== null);

        return $this->leverageRepository->update($leverage, $attributes);
    }
}
