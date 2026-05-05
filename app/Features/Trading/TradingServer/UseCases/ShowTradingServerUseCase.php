<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\DTOs\TradingServerDTO;
use App\Features\Trading\TradingServer\Exceptions\TradingServerNotFoundException;
use App\Features\Trading\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\Trading\TradingServer\Http\V1\Commands\ShowTradingServerCommand;
use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

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
