<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\ListPlatformsCommand;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPlatformsUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(ListPlatformsCommand $command): LengthAwarePaginator
    {
        return $this->platformFactory->make()->paginate(
            filters: [
                'name' => $command->name,
                'custom_name' => $command->customName,
                'code' => $command->code,
                'volume_factor' => $command->volumeFactor,
                'is_active' => $command->isActive,
            ],
            perPage: $command->perPage,
        );
    }
}
