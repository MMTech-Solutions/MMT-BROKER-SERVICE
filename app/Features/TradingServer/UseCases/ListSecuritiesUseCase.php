<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\DTOs\SecurityDTO;
use App\Features\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\ListSecuritiesCommand;
use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use Spatie\LaravelData\PaginatedDataCollection;

class ListSecuritiesUseCase
{
    private SecurityRepositoryInterface $securityRepository;

    public function __construct(
        SecurityRepositoryFactory $securityRepositoryFactory,
    ) {
        $this->securityRepository = $securityRepositoryFactory->make();
    }

    public function execute(ListSecuritiesCommand $command): PaginatedDataCollection
    {
        $securityModels = $this->securityRepository->paginate(
            filters: [
                'trading_server_id' => $command->TradingServerId,
                'name' => $command->name,
            ],
            perPage: $command->perPage,
        );

        return SecurityDTO::collect(
            $securityModels,
            PaginatedDataCollection::class
        );
    }
}
