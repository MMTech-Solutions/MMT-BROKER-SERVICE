<?php

namespace App\Features\Manager\UseCases;

use App\Features\Manager\Exceptions\ManagerNotFoundException;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Http\V1\Commands\UpdateManagerCommand;
use App\Features\Manager\Models\Manager;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Platform\Services\FindPlatformByIdService;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Exception;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;

class UpdateManagerUseCase
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
        private readonly FindPlatformByIdService $findPlatformByIdService,
    ) {
        $this->managerRepository = $managerRepositoryFactory->make();
    }

    public function execute(UpdateManagerCommand $command): Manager
    {
        $manager = $this->managerRepository->findById($command->managerId);

        if ($manager === null) {
            throw new ManagerNotFoundException();
        }

        $platform = $this->findPlatformByIdService->execute($manager->platform_id);

        $attributes = [
            'environment' => $command->environment ?? $manager->environment,
            'is_active' => $command->isActive ?? $manager->is_active,
        ];

        // If any of the attributes are set, connect to the new broker
        if ($command->host || $command->port || $command->username || $command->password) {

            $oldConnectionId = $manager->connection_id;
            
            $attributes = array_merge($attributes, $this->connectAttributes($command, $manager));

            // Test the connection and set the new connection_id in the attributes
            $this->testConnection($platform->toEnum(), $attributes, $platform->id);

            // Disconnect by the old `connection_id`. Decrements ref_count; when it reaches 0 the Bridge process is closed
            $this->tradingServiceSessionFactory->disconnect($oldConnectionId);
        }
        
        return $this->managerRepository->update($manager, $attributes);
    }

    private function connectAttributes(UpdateManagerCommand $command, Manager $manager): array
    {
        $attributes = [];
        
        $attributes['host'] = $command->host ?? $manager->host;
        $attributes['port'] = $command->port ?? $manager->port;
        $attributes['username'] = $command->username ?? $manager->username;
        $attributes['password'] = $command->password ?? $manager->password;

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
