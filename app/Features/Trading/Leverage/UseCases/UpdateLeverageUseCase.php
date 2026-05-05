<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Http\V1\Commands\UpdateLeverageCommand;
use App\Features\Trading\Leverage\Models\Leverage;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;

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
