<?php

namespace App\Features\TradingServer\UseCases;

use App\Features\Platform\Services\FindPlatformByIdService;
use App\Features\TradingServer\Exceptions\TradingServerNotFoundException;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Http\V1\Commands\UpdateTradingServerCommand;
use App\Features\TradingServer\DTOs\TradingServerDTO;
use App\Features\TradingServer\Models\TradingServer;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Exception;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;

class UpdateTradingServerUseCase
{
    private TradingServerRepositoryInterface $tradingServerRepository;

    public function __construct(
        private readonly TradingServerRepositoryFactory $tradingServerRepositoryFactory,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
        private readonly FindPlatformByIdService $findPlatformByIdService
    ) {
        $this->tradingServerRepository = $tradingServerRepositoryFactory->make();
    }

    public function execute(UpdateTradingServerCommand $command): TradingServerDTO
    {
        $tradingServer = $this->tradingServerRepository->findById($command->TradingServerId);

        if ($tradingServer === null) {
            throw new TradingServerNotFoundException;
        }

        $platform = $this->findPlatformByIdService->execute($tradingServer->platform_id);

        $attributes = [
            'environment' => $command->environment ?? $tradingServer->environment,
            'is_active' => $command->isActive ?? $tradingServer->is_active,
        ];

        // If any of the attributes are set, connect to the new broker
        if ($command->host || $command->port || $command->username || $command->password) {

            $oldConnectionId = $tradingServer->connection_id;

            $attributes = array_merge($attributes, $this->connectAttributes($command, $tradingServer));

            // Test the connection and set the new connection_id in the attributes
            $this->testConnection($platform->type, $attributes, $platform->id);

            // Disconnect by the old `connection_id`. Decrements ref_count; when it reaches 0 the Bridge process is closed
            $this->tradingServiceSessionFactory->disconnect($oldConnectionId);
        }

        $tradingServerModel = $this->tradingServerRepository->update($tradingServer, $attributes);
        return TradingServerDTO::from($tradingServerModel);
    }

    private function connectAttributes(UpdateTradingServerCommand $command, TradingServer $tradingServer): array
    {
        $attributes = [];

        $attributes['host'] = $command->host ?? $tradingServer->host;
        $attributes['port'] = $command->port ?? $tradingServer->port;
        $attributes['username'] = $command->username ?? $tradingServer->username;
        $attributes['password'] = $command->password ?? $tradingServer->password;

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
        } catch (Exception $e) {
            throw new ConnectionFailsException($e->getMessage());
        }
    }
}
