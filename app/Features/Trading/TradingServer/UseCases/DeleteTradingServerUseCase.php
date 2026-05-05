<?php

namespace App\Features\Trading\TradingServer\UseCases;

use App\Features\Trading\TradingServer\Exceptions\TradingServerNotFoundException;
use App\Features\Trading\TradingServer\Http\V1\Commands\DeleteTradingServerCommand;
use App\Features\Trading\TradingServer\Factories\TradingServerRepositoryFactory;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;

class DeleteTradingServerUseCase
{
    private TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $TradingServerRepositoryFactory,
        private readonly TradingServiceSessionFactory $sdkSessionFactory,
    ) {
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
    }

    public function execute(DeleteTradingServerCommand $command): void
    {
        $TradingServer = $this->TradingServerRepository->findById($command->TradingServerId);

        if ($TradingServer === null) {
            throw new TradingServerNotFoundException();
        }

        $this->sdkSessionFactory->disconnect($TradingServer->connection_id);

        $this->TradingServerRepository->deleteById($TradingServer->id);
    }
}
