<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\SharedFeatures\TradingService\Exceptions\TradingServiceException;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;

class InitializeTradingServerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    protected TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly TradingServerRepositoryFactory $TradingServerRepositoryFactory,
        private readonly PopulateGroupContentService $populateGroupContentService,
    ) {
        $this->serverGroupRepository = $this->serverGroupRepositoryFactory->make();
        $this->TradingServerRepository = $this->TradingServerRepositoryFactory->make();
    }

    /**
     * @throws TradingServiceException
     */
    public function execute(string $tradingServerId): void
    {
        $TradingServer = $this->TradingServerRepository->findById($tradingServerId);
        $tradingService = $this->tradingServiceFactory->make(
            $TradingServer->platform->toEnum(),
            $TradingServer->connection_id
        );

        $groups = $this->getGroupHierarchyItems($tradingService);

        foreach ($groups as $group) {
            $serverGroupModel = $this->serverGroupRepository->basicCreate($group->name, $tradingServerId);
            $this->populateGroupContentService->execute($serverGroupModel, $group, $tradingServerId);
        }
    }

    /**
     * @return HierarchyGroupItem[]
     *
     * @throws TradingServiceException
     */
    private function getGroupHierarchyItems(MT5TradingServiceInterface $tradingService): array
    {
        $result = $tradingService->getGroupHierarchy();

        if ($result->isFailure()) {
            throw new TradingServiceException($result->getMessage());
        }

        /** @var HierarchyGroupItem[] */
        return $result->getMappedData(HierarchyGroupItem::class);
    }
}
