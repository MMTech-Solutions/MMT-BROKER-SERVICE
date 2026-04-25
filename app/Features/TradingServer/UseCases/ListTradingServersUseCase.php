<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\ListTradingServersCommand;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListTradingServersUseCase
{
    private TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        TradingServerRepositoryFactory $TradingServerRepositoryFactory,
    ) {
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
    }

    public function execute(ListTradingServersCommand $command): LengthAwarePaginator
    {
        return $this->TradingServerRepository->paginate(
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
    }
}
