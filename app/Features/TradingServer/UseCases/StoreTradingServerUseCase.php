<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\StoreTradingServerCommand;
use App\Features\TradingServer\Models\TradingServer;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\Platform\Services\FindPlatformByIdService;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;
use Exception;

class StoreTradingServerUseCase
{
    private TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $TradingServerRepositoryFactory,
        private readonly FindPlatformByIdService $findPlatformByIdService,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
    ) {
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
    }

    public function execute(StoreTradingServerCommand $command): TradingServer
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

        return $this->TradingServerRepository->create([
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
