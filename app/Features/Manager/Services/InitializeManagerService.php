<?php

namespace App\Features\Manager\Services;

use App\Features\Manager\Exceptions\TradingServiceException;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Factories\ServerGroupRepositoryFactory;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;

class InitializeManagerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;
    protected ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
        private readonly PopulateGroupContentService $populateGroupContentService,
    ) {
        $this->serverGroupRepository = $this->serverGroupRepositoryFactory->make();
        $this->managerRepository     = $this->managerRepositoryFactory->make();
    }

    /**
     * @throws TradingServiceException
     */
    public function execute(string $managerId): void
    {
        $manager        = $this->managerRepository->findById($managerId);
        $tradingService = $this->tradingServiceFactory->make(
            $manager->platform->toEnum(),
            $manager->connection_id
        );

        $groups = $this->getServerGroups($tradingService);

        foreach ($groups as $group) {
            $serverGroupModel = $this->serverGroupRepository->basicCreate($group->name, $managerId);
            $this->populateGroupContentService->execute($serverGroupModel, $group, $managerId, $tradingService);
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
