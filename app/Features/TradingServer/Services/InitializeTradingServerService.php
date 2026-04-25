<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Exceptions\TradingServiceException;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;

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
        $this->TradingServerRepository     = $this->TradingServerRepositoryFactory->make();
    }

    /**
     * @throws TradingServiceException
     */
    public function execute(string $TradingServerId): void
    {
        $TradingServer        = $this->TradingServerRepository->findById($TradingServerId);
        $tradingService = $this->tradingServiceFactory->make(
            $TradingServer->platform->toEnum(),
            $TradingServer->connection_id
        );

        $groups = $this->getServerGroups($tradingService);

        foreach ($groups as $group) {
            $serverGroupModel = $this->serverGroupRepository->basicCreate($group->name, $TradingServerId);
            $this->populateGroupContentService->execute($serverGroupModel, $group, $TradingServerId, $tradingService);
        }
    }

    /**
     * @throws TradingServiceException
     * @return GroupItem[]
     */
    private function getServerGroups(MT5TradingServiceInterface $tradingService): array
    {
        $result = $tradingService->listGroups();

        if (!$result->isSuccess()) {
            throw new TradingServiceException($result->getMessage());
        }

        return $result->getData(GroupItem::class);
    }
}
