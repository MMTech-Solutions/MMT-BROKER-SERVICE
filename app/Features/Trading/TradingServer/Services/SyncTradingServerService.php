<?php

namespace App\Features\Trading\TradingServer\Services;

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

class SyncTradingServerService
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
        private readonly PopulateGroupContentService $populateGroupContentService,
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

        // Si la API no devuelve grupos, eliminar todo lo que haya en DB para este TradingServer
        if (count($groups) === 0) {
            $this->serverGroupRepository->deleteAllByTradingServerId($tradingServerId);
            $this->symbolRepository->deleteAllByTradingServerId($tradingServerId);
            $this->securityRepository->deleteAllByTradingServerId($tradingServerId);
            $this->markInitializedAtIfFirstSuccess($tradingServer);

            return;
        }

        // Eliminar los grupos presentes en DB pero ausentes en la API
        $groupNames = array_map(fn (HierarchyGroupItem $group) => $group->name, $groups);
        $groupsToDelete = $this->serverGroupRepository->getDiff($tradingServerId, $groupNames);

        foreach ($groupsToDelete as $group) {
            $this->clearGroupContent($group);
            $this->serverGroupRepository->deleteById($group->id);
        }

        // Para cada grupo de la API: sincronizar su contenido si ya existe, o crearlo si es nuevo
        foreach ($groups as $groupItem) {
            $existingGroup = $this->serverGroupRepository->findByName($groupItem->name, $tradingServerId);

            if ($existingGroup !== null) {
                $this->populateGroupContentService->execute($existingGroup, $groupItem, $tradingServerId);
            } else {
                $newGroup = $this->serverGroupRepository->basicCreate($groupItem->name, $tradingServerId);
                $this->populateGroupContentService->execute($newGroup, $groupItem, $tradingServerId);
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

    /**
     * Elimina todas las securities y sus symbols asociados al grupo, sin tocar el ServerGroup en sí.
     * Preserva la configuración manual del grupo (meta_name, is_active, etc.).
     */
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
