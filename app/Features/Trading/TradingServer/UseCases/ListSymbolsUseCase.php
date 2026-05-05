<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\DTOs\SymbolDTO;
use App\Features\Trading\TradingServer\Factories\SymbolRepositoryFactory;
use App\Features\Trading\TradingServer\Http\V1\Commands\ListSymbolsCommand;
use App\Features\Trading\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use Spatie\LaravelData\PaginatedDataCollection;

class ListSymbolsUseCase
{
    private SymbolRepositoryInterface $symbolRepository;

    public function __construct(
        SymbolRepositoryFactory $symbolRepositoryFactory,
    ) {
        $this->symbolRepository = $symbolRepositoryFactory->make();
    }

    public function execute(ListSymbolsCommand $command): PaginatedDataCollection
    {
        $symbolModels = $this->symbolRepository->paginate(
            filters: [
                'trading_server_id' => $command->TradingServerId,
                'name' => $command->name,
                'alpha' => $command->alpha,
                'stype' => $command->stype,
            ],
            perPage: $command->perPage,
        );

        return SymbolDTO::collect(
            $symbolModels,
            PaginatedDataCollection::class
        );
    }
}
