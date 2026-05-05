<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\DTOs\PlatformDTO;
use App\Features\Platform\Http\V1\Commands\ListPlatformsCommand;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use App\Features\Platform\QueryObjects\ListPlatformsQuery;
use Illuminate\Pagination\LengthAwarePaginator;
use App\SharedFeatures\User\UserContext;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ListPlatformsUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
        private readonly ListPlatformsQuery $listPlatformsQuery,
        private readonly UserContext $userContext,
    ) {
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(ListPlatformsCommand $command)
    {
        $platformsPaginator = $this->listPlatformsQuery->handle(
            filters: [
                'name' => $command->name,
                'custom_name' => $command->customName,
                'code' => $command->code,
                'volume_factor' => $command->volumeFactor,
                'is_active' => $command->isActive,
            ],
            perPage: $command->perPage,
        );

        return PlatformDTO::collect(
            $platformsPaginator,
            PaginatedDataCollection::class
        )
        ->includeWhen('is_active', fn() => $this->userContext->can('platform.update'));
    }
}
