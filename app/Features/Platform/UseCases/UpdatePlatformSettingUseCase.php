<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Exceptions\PlatformSettingNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\UpdatePlatformSettingCommand;
use App\Features\Platform\Models\Platform;
use App\Features\Platform\Models\PlatformSetting;
use App\Features\Platform\Repositories\Contracts\PlatformSettingRepositoryInterface;
use App\SharedFeatures\TradingService\Exceptions\ConnectionFailsException;
use App\SharedFeatures\TradingService\Factories\TradingServiceSessionFactory;
use Exception;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;

class UpdatePlatformSettingUseCase
{
    private PlatformSettingRepositoryInterface $settingRepository;

    public function __construct(
        private readonly PlatformFactory $platformFactory,
        private readonly TradingServiceSessionFactory $tradingServiceSessionFactory
    ) {
        $this->settingRepository = $this->platformFactory->makeSetting();
    }

    public function execute(UpdatePlatformSettingCommand $command): PlatformSetting
    {
        $platform = $this->getPlatform($command->platformId);

        $setting = $this->getSetting($command->settingId, $command->platformId);

        $platformEnum = $platform->toEnum();

        $attributes = [
            'environment' => $command->environment ?? $setting->environment,
            'is_active' => $command->isActive ?? $setting->is_active,
        ];

        // If any of the attributes are set, connect to the new broker
        if ($command->host || $command->port || $command->username || $command->password) {

            $oldConnectionId = $setting->connection_id;
            
            $attributes = array_merge($attributes, $this->connectAttributes($command, $setting));

            // Test the connection and set the new connection_id in the attributes
            $this->testConnection($platformEnum, $attributes, $platform->id);

            // Disconnect by the old `connection_id`. Decrements ref_count; when it reaches 0 the Bridge process is closed
            $this->tradingServiceSessionFactory->disconnect($oldConnectionId);
        }
        
        return $this->settingRepository->update($setting, $attributes);
    }

    private function getSetting(string $settingUuid, string $platformUuid): PlatformSetting
    {
        $setting = $this->settingRepository->findById($settingUuid);

        if ($setting === null || $setting->platform_id !== $platformUuid) {
            throw new PlatformSettingNotFoundException;
        }

        return $setting;
    }

    private function getPlatform(string $platformUuid): Platform
    {
        $platform =$this->platformFactory->make()
            ->findById($platformUuid);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        return $platform;
    }

    private function connectAttributes(UpdatePlatformSettingCommand $command, PlatformSetting $setting): array
    {
        $attributes = [];
        
        $attributes['host'] = $command->host ?? $setting->host;
        $attributes['port'] = $command->port ?? $setting->port;
        $attributes['username'] = $command->username ?? $setting->username;
        $attributes['password'] = $command->password ?? $setting->password;

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
