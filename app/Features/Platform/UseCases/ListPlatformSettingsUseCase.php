<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\ListPlatformSettingsCommand;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPlatformSettingsUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(ListPlatformSettingsCommand $command): LengthAwarePaginator
    {
        $platform = $this->platformFactory->make()->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        return $this->platformFactory->makeSetting()->paginate(
            filters: [
                'host' => $command->host,
                'username' => $command->username,
                'port' => $command->port,
                'enviroment' => $command->enviroment,
                'is_active' => $command->isActive,
            ],
            perPage: $command->perPage,
            platformId: $command->platformId,
        );
    }
}
