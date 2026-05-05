<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\DTOs\SecurityDTO;
use App\Features\Trading\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\Trading\TradingServer\Http\V1\Commands\ListServerGroupSecuritiesCommand;
use App\Features\Trading\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use Spatie\LaravelData\PaginatedDataCollection;

class ListServerGroupSecuritiesUseCase
{
    private SecurityRepositoryInterface $securityRepository;

    public function __construct(
        SecurityRepositoryFactory $securityRepositoryFactory,
    ) {
        $this->securityRepository = $securityRepositoryFactory->make();
    }

    public function execute(ListServerGroupSecuritiesCommand $command): PaginatedDataCollection
    {
        $securities = $this->securityRepository->paginate(
            filters: [
                'trading_server_id' => $command->TradingServerId,
                'server_group_id' => $command->serverGroupId,
                'name' => $command->name,
            ],
            perPage: $command->perPage,
        );

        return SecurityDTO::collect(
            $securities,
            PaginatedDataCollection::class
        );
    }
}
