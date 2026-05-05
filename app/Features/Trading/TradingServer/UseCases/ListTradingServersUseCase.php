<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\DTOs\TradingServerDTO;
use App\Features\Trading\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\Trading\TradingServer\Http\V1\Commands\ListTradingServersCommand;
use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\PaginatedDataCollection;

class ListTradingServersUseCase
{
    private TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        TradingServerRepositoryFactory $TradingServerRepositoryFactory,
    ) {
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
    }

    public function execute(ListTradingServersCommand $command) : PaginatedDataCollection
    {
        $tradingServers = $this->TradingServerRepository->paginate(
            filters: [
                'host' => $command->host,
                'username' => $command->username,
                'port' => $command->port,
                'enviroment' => $command->enviroment,
                'is_active' => $command->isActive,
                'platform_id' => $command->platformId,
            ],
            perPage: $command->perPage,
        );

        return TradingServerDTO::collect(
            $tradingServers,
            PaginatedDataCollection::class
        );
    }
}
