<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\DTOs\SecurityDTO;
use App\Features\Trading\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\Trading\TradingServer\Http\V1\Commands\ListSecuritiesCommand;
use App\Features\Trading\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
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
