<?php

namespace App\Features\Leverage\UseCases;

use App\Features\Leverage\Factories\LeverageRepositoryFactory;
use App\Features\Leverage\Http\V1\Commands\ListLeveragesCommand;
use App\Features\Leverage\Repositories\Contracts\LeverageRepositoryInterface;
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
