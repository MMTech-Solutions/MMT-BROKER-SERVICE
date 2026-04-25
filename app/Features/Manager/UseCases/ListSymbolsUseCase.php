<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\SymbolRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\ListSymbolsCommand;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSymbolsUseCase
{
    private SymbolRepositoryInterface $symbolRepository;

    public function __construct(
        SymbolRepositoryFactory $symbolRepositoryFactory,
    ) {
        $this->symbolRepository = $symbolRepositoryFactory->make();
    }

    public function execute(ListSymbolsCommand $command): LengthAwarePaginator
    {
        return $this->symbolRepository->paginate(
            filters: [
                'manager_id' => $command->managerId,
                'name' => $command->name,
                'alpha' => $command->alpha,
                'stype' => $command->stype,
            ],
            perPage: $command->perPage,
        );
    }
}
