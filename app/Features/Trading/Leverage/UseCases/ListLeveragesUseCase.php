<?php

namespace App\Features\Trading\Leverage\UseCases;

use App\Features\Trading\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Trading\Leverage\Http\V1\Commands\ListLeveragesCommand;
use App\Features\Trading\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListLeveragesUseCase
{
    private LeverageRepositoryInterface $leverageRepository;

    public function __construct(
        private readonly LeverageRepositoryFactory $leverageRepositoryFactory,
    ) {
        $this->leverageRepository = $leverageRepositoryFactory->make();
    }

    public function execute(ListLeveragesCommand $command): LengthAwarePaginator
    {
        return $this->leverageRepository->paginate(
            filters: [
                'name'  => $command->name,
                'value' => $command->value,
            ],
            perPage: $command->perPage,
        );
    }
}
