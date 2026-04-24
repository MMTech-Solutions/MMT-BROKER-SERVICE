<?php

namespace App\Features\Manager\Services;

use App\Features\Manager\Exceptions\TradingServiceException;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Factories\SecurityRepositoryFactory;
use App\Features\Manager\Factories\ServerGroupRepositoryFactory;
use App\Features\Manager\Factories\SymbolRepositoryFactory;
use App\Features\Manager\Models\Security;
use App\Features\Manager\Models\ServerGroup;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;

class SyncManagerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;
    protected SymbolRepositoryInterface $symbolRepository;
    protected SecurityRepositoryInterface $securityRepository;
    protected ManagerRepositoryInterface $managerRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
        private readonly PopulateGroupContentService $populateGroupContentService,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
        $this->symbolRepository      = $symbolRepositoryFactory->make();
        $this->securityRepository    = $securityRepositoryFactory->make();
        $this->managerRepository     = $managerRepositoryFactory->make();
    }

    /**
     * @throws TradingServiceException
     */
    public function execute(string $managerId): void
    {
        $manager = $this->managerRepository->findById($managerId);

        $tradingService = $this->tradingServiceFactory->make(
            $manager->platform->toEnum(),
            $manager->connection_id
        );

        $groupsResult = $tradingService->listGroups();

        if (!$groupsResult->isSuccess()) {
            throw new TradingServiceException($groupsResult->getMessage());
        }

        $groups = $groupsResult->getData(GroupItem::class);

        // Si la API no devuelve grupos, eliminar todo lo que haya en DB para este manager
        if (count($groups) === 0) {
            $this->serverGroupRepository->deleteAllByManagerId($managerId);
            $this->symbolRepository->deleteAllByManagerId($managerId);
            return;
        }

        // Eliminar los grupos presentes en DB pero ausentes en la API
        $groupNames    = array_map(fn(GroupItem $group) => $group->name, $groups);
        $groupsToDelete = $this->serverGroupRepository->getDiff($managerId, $groupNames);

        foreach ($groupsToDelete as $group) {
            $this->clearGroupContent($group);
            $this->serverGroupRepository->deleteById($group->id);
        }

        // Para cada grupo de la API: sincronizar su contenido si ya existe, o crearlo si es nuevo
        foreach ($groups as $groupItem) {
            $existingGroup = $this->serverGroupRepository->findByName($groupItem->name, $managerId);

            if ($existingGroup !== null) {
                $this->clearGroupContent($existingGroup);
                $this->populateGroupContentService->execute($existingGroup, $groupItem, $managerId, $tradingService);
            } else {
                $newGroup = $this->serverGroupRepository->basicCreate($groupItem->name, $managerId);
                $this->populateGroupContentService->execute($newGroup, $groupItem, $managerId, $tradingService);
            }
        }
    }

    /**
     * Elimina todas las securities y sus symbols asociados al grupo, sin tocar el ServerGroup en sí.
     * Preserva la configuración manual del grupo (meta_name, is_active, etc.).
     */
    private function clearGroupContent(ServerGroup $serverGroup): void
    {
        $securities  = $serverGroup->securities;
        $securityIds = $securities->pluck('id')->toArray();
        $symbolIds   = $securities->flatMap(
            fn(Security $security) => $security->symbols()->pluck('id')
        )->toArray();

        $this->symbolRepository->deleteSymbolsByIds($symbolIds);
        $this->securityRepository->deleteSecuritiesByIds($securityIds);
    }
}
