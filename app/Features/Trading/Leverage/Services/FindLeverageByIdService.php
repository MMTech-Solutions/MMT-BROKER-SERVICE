<?php

namespace App\Features\Trading\Leverage\Services;

use App\Features\Trading\Leverage\Exceptions\LeverageNotFoundException;
use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use App\Features\Trading\Leverage\Models\Leverage;

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