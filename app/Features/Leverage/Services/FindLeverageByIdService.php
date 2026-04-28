<?php

namespace App\Features\Leverage\Services;

use App\Features\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use App\Features\Leverage\Models\Leverage;

class FindLeverageByIdService
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(string $leverageId): Leverage
    {
        return $this->leverageRepository->findById($leverageId) ?? throw new LeverageNotFoundException();
    }
}