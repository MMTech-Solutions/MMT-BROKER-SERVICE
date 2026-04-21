<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\StorePlatformSettingCommand;
use App\Features\Platform\Models\PlatformSetting;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;
use Exception;

class StorePlatformSettingUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory,
    ) {}

    public function execute(StorePlatformSettingCommand $command): PlatformSetting
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException();
        }

        $platformEnum = $platform->toEnum();

        $connectBrokerCommand = new ConnectBrokerCommand(
            platform_type: $platformEnum,
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

        return $this->platformFactory->makeSetting()->create([
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
