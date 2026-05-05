<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\Exceptions\ServerGroupNotFoundException;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListServerGroupLeveragesService
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $serverGroupId, array $filters, int $perPage): LengthAwarePaginator
    {
        $serverGroup = $this->serverGroupRepository->findByUuid($serverGroupId)
            ?? throw new ServerGroupNotFoundException();

        return $serverGroup->leverages()
            ->when(($filters['name'] ?? null) !== null, fn ($q) => $q->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(($filters['value'] ?? null) !== null, fn ($q) => $q->where('value', (int) $filters['value']))
            ->orderBy('value')
            ->paginate($perPage);
    }
}
