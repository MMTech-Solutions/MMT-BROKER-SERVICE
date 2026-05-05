<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\DTOs\TradingServerDTO;
use App\Features\TradingServer\Exceptions\TradingServerNotFoundException;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\ShowTradingServerCommand;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

class ShowTradingServerUseCase
{
    private TradingServerRepositoryInterface $tradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $tradingServerRepositoryFactory,
    ) {
        $this->tradingServerRepository = $tradingServerRepositoryFactory->make();
    }

    public function execute(ShowTradingServerCommand $command): TradingServerDTO
    {
        $tradingServer = $this->tradingServerRepository->findById($command->TradingServerId) ?? throw new TradingServerNotFoundException();
        return TradingServerDTO::from($tradingServer);
    }
}
