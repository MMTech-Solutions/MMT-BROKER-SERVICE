<?php

namespace App\Features\Trading\TradingServer\Actions;

use App\Features\Trading\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\SymbolRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\Trading\TradingServer\Models\Security;
use App\Features\Trading\TradingServer\Models\ServerGroup;
use App\Features\Trading\TradingServer\Models\TradingServer;
use App\Features\Trading\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\SharedFeatures\TradingService\Exceptions\TradingServiceException;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;

class SyncTradingServerAction
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    protected SymbolRepositoryInterface $symbolRepository;

    protected SecurityRepositoryInterface $securityRepository;

    protected TradingServerRepositoryInterface $tradingServerRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly TradingServerRepositoryFactory $tradingServerRepositoryFactory,
        private readonly PopulateGroupContentAction $populateGroupContentAction,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
        $this->symbolRepository = $symbolRepositoryFactory->make();
        $this->securityRepository = $securityRepositoryFactory->make();
        $this->tradingServerRepository = $tradingServerRepositoryFactory->make();
    }

    /**
     * @throws TradingServiceException
     */
    public function execute(string $tradingServerId): void
    {
        $tradingServer = $this->tradingServerRepository->findById($tradingServerId);

        $tradingService = $this->tradingServiceFactory->make(
            $tradingServer->platform->toEnum(),
            $tradingServer->connection_id
        );

        $groupsResult = $tradingService->getGroupHierarchy();

        if ($groupsResult->isFailure()) {
            throw new TradingServiceException($groupsResult->getMessage());
        }

        /** @var HierarchyGroupItem[] $groups */
        $groups = $groupsResult->getMappedData(HierarchyGroupItem::class);

        if (count($groups) === 0) {
            $this->serverGroupRepository->deleteAllByTradingServerId($tradingServerId);
            $this->symbolRepository->deleteAllByTradingServerId($tradingServerId);
            $this->securityRepository->deleteAllByTradingServerId($tradingServerId);
            $this->markInitializedAtIfFirstSuccess($tradingServer);

            return;
        }

        $groupNames = array_map(fn (HierarchyGroupItem $group) => $group->name, $groups);
        $groupsToDelete = $this->serverGroupRepository->getDiff($tradingServerId, $groupNames);

        foreach ($groupsToDelete as $group) {
            $this->clearGroupContent($group);
            $this->serverGroupRepository->deleteById($group->id);
        }

        foreach ($groups as $groupItem) {
            $existingGroup = $this->serverGroupRepository->findByName($groupItem->name, $tradingServerId);

            if ($existingGroup !== null) {
                $this->populateGroupContentAction->execute($existingGroup, $groupItem, $tradingServerId);
            } else {
                $newGroup = $this->serverGroupRepository->basicCreate($groupItem->name, $tradingServerId);
                $this->populateGroupContentAction->execute($newGroup, $groupItem, $tradingServerId);
            }
        }

        $this->markInitializedAtIfFirstSuccess($tradingServer);
    }

    private function markInitializedAtIfFirstSuccess(TradingServer $tradingServer): void
    {
        if ($tradingServer->initialized_at !== null) {
            return;
        }

        $this->tradingServerRepository->update($tradingServer, [
            'initialized_at' => now(),
        ]);
    }

    private function clearGroupContent(ServerGroup $serverGroup): void
    {
        $securities = $serverGroup->securities;
        $securityIds = $securities->pluck('id')->toArray();
        $symbolIds = $securities->flatMap(
            fn (Security $security) => $security->symbols()->pluck('symbols.id')
        )->toArray();

        if (count($symbolIds) > 0) {
            $this->symbolRepository->deleteSymbolsByIds($symbolIds);
        }

        if (count($securityIds) > 0) {
            $this->securityRepository->deleteSecuritiesByIds($securityIds);
        }
    }
}
