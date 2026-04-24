<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\StoreManagerCommand;
use App\Features\Manager\Models\Manager;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Platform\Services\FindPlatformByIdService;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;
use Exception;

class StoreManagerUseCase
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
        private readonly FindPlatformByIdService $findPlatformByIdService,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
    ) {
        $this->managerRepository = $managerRepositoryFactory->make();
    }

    public function execute(StoreManagerCommand $command): Manager
    {
        $platform = $this->findPlatformByIdService->execute($command->platformId);

        $connectBrokerCommand = new ConnectBrokerCommand(
            platform_type: $platform->toEnum(),
            server: $command->host,
            port: $command->port,
            login: $command->username,
            password: $command->password,
            name: 'broker-'.$platform->id.'-'.now()->timestamp,
        );

        $connectionId = null;
        
        try {
            $this->tradingServiceSessionFactory->make($connectBrokerCommand, $connectionId);
        }
        catch(Exception $e) {
            throw new ConnectionFailsException($e->getMessage());
        }

        return $this->managerRepository->create([
            'platform_id' => $command->platformId,
            'host' => $command->host,
            'port' => $command->port,
            'username' => $command->username,
            'password' => $command->password,
            'connection_id' => $connectionId,
            'environment' => $command->environment,
            'is_active' => $command->isActive,
        ]);
    }
}
