<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\TradingServer\Exceptions\TradingServerNotFoundException;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\UpdateTradingServerCommand;
use App\Features\TradingServer\Models\TradingServer;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\Platform\Services\FindPlatformByIdService;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Exception;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;

class UpdateTradingServerUseCase
{
    private TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $TradingServerRepositoryFactory,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
        private readonly FindPlatformByIdService $findPlatformByIdService,
    ) {
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
    }

    public function execute(UpdateTradingServerCommand $command): TradingServer
    {
        $TradingServer = $this->TradingServerRepository->findById($command->TradingServerId);

        if ($TradingServer === null) {
            throw new TradingServerNotFoundException();
        }

        $platform = $this->findPlatformByIdService->execute($TradingServer->platform_id);

        $attributes = [
            'environment' => $command->environment ?? $TradingServer->environment,
            'is_active' => $command->isActive ?? $TradingServer->is_active,
        ];

        // If any of the attributes are set, connect to the new broker
        if ($command->host || $command->port || $command->username || $command->password) {

            $oldConnectionId = $TradingServer->connection_id;
            
            $attributes = array_merge($attributes, $this->connectAttributes($command, $TradingServer));

            // Test the connection and set the new connection_id in the attributes
            $this->testConnection($platform->toEnum(), $attributes, $platform->id);

            // Disconnect by the old `connection_id`. Decrements ref_count; when it reaches 0 the Bridge process is closed
            $this->tradingServiceSessionFactory->disconnect($oldConnectionId);
        }
        
        return $this->TradingServerRepository->update($TradingServer, $attributes);
    }

    private function connectAttributes(UpdateTradingServerCommand $command, TradingServer $TradingServer): array
    {
        $attributes = [];
        
        $attributes['host'] = $command->host ?? $TradingServer->host;
        $attributes['port'] = $command->port ?? $TradingServer->port;
        $attributes['username'] = $command->username ?? $TradingServer->username;
        $attributes['password'] = $command->password ?? $TradingServer->password;

        return $attributes;
    }

    private function testConnection(PlatformEnum $platformEnum, array &$attributes, string|int $platformId): void
    {
        $connectBrokerCommand = new ConnectBrokerCommand(
            platform_type: $platformEnum,
            server: $attributes['host'],
            port: $attributes['port'],
            login: $attributes['username'],
            password: $attributes['password'],
            name: 'broker-'.$platformId.'-'.now()->timestamp,
        );
        
        try {
            $this->tradingServiceSessionFactory->make($connectBrokerCommand, $connectionId);
            $attributes['connection_id'] = $connectionId;
        }
        catch(Exception $e) {
            throw new ConnectionFailsException($e->getMessage());
        }
    }
}
