<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Factories\SymbolRepositoryFactory;
use App\Features\TradingServer\Factories\TradingServerRepositoryFactory;
use App\Features\TradingServer\Models\Security;
use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\TradingServerRepositoryInterface;
use App\SharedFeatures\TradingService\Exceptions\TradingServiceException;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;

class SyncTradingServerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;

    protected SymbolRepositoryInterface $symbolRepository;

    protected SecurityRepositoryInterface $securityRepository;

    protected TradingServerRepositoryInterface $TradingServerRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly TradingServerRepositoryFactory $TradingServerRepositoryFactory,
        private readonly PopulateGroupContentService $populateGroupContentService,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
        $this->symbolRepository = $symbolRepositoryFactory->make();
        $this->securityRepository = $securityRepositoryFactory->make();
        $this->TradingServerRepository = $TradingServerRepositoryFactory->make();
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
                $this->clearGroupContent($existingGroup);
                $this->populateGroupContentService->execute($existingGroup, $groupItem, $tradingServerId);
            } else {
                $newGroup = $this->serverGroupRepository->basicCreate($groupItem->name, $tradingServerId);
                $this->populateGroupContentService->execute($newGroup, $groupItem, $tradingServerId);
            }
        }
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
            fn (Security $security) => $security->symbols()->pluck('id')
        )->toArray();

        $this->symbolRepository->deleteSymbolsByIds($symbolIds);
        $this->securityRepository->deleteSecuritiesByIds($securityIds);
    }
}
