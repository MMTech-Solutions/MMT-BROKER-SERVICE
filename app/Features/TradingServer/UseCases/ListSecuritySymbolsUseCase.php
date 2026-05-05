<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\DTOs\SymbolDTO;
use App\Features\TradingServer\Factories\SymbolRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\ListSecuritySymbolsCommand;
use App\Features\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use Spatie\LaravelData\PaginatedDataCollection;

class ListSecuritySymbolsUseCase
{
    private SymbolRepositoryInterface $symbolRepository;

    public function __construct(
        SymbolRepositoryFactory $symbolRepositoryFactory,
    ) {
        $this->symbolRepository = $symbolRepositoryFactory->make();
    }

    public function execute(ListSecuritySymbolsCommand $command): PaginatedDataCollection
    {
        $symbolModels = $this->symbolRepository->paginate(
            filters: [
                'trading_server_id' => $command->TradingServerId,
                'security_id' => $command->securityId,
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
