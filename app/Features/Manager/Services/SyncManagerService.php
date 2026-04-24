<?php

namespace App\Features\Manager\Services;

use App\Features\Manager\Factories\ServerGroupRepositoryFactory;
use App\Features\Manager\Factories\SecurityRepositoryFactory;
use App\Features\Manager\Factories\SymbolRepositoryFactory;
use App\Features\Platform\Services\FindPlatformServerSettingService;
use App\Features\Manager\Jobs\InitializeGroupJob;
use App\Features\Manager\Models\Security;
use App\Features\Manager\Models\ServerGroup;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Illuminate\Database\Eloquent\Collection;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;
use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;

class SyncManagerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;
    protected SymbolRepositoryInterface $symbolRepository;
    protected SecurityRepositoryInterface $securityRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly FindPlatformServerSettingService $findPlatformServerSettingService
    ){
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
        $this->symbolRepository = $symbolRepositoryFactory->make();
        $this->securityRepository = $securityRepositoryFactory->make();
    }

    public function execute(string $platformSettingId): void
    {
        $platformSetting = $this->findPlatformServerSettingService->execute($platformSettingId);
        $platform = $platformSetting->platform;

        $tradingService = $this->tradingServiceFactory->make($platform->toEnum(), $platformSetting->connection_id);

        $groupsResult = $tradingService->listGroups();

        if( !$groupsResult->isSuccess() ) {
            throw new \Exception('Failed to list groups from trading service');
        }

        $groups = $groupsResult->getData(GroupItem::class);

        // Si en la api externa no hay ningun grupo, sincronizar implica eliminar los que estan abajo (en DB)
        if( count($groups) === 0 ) {
            $this->serverGroupRepository->deleteAllByPlatformSettingId($platformSetting->id);
            $this->symbolRepository->deleteAllByPlatformSettingId($platformSetting->id);
            return;
        }

        // Nombre de los grupos presentes en la api externa
        $groupNames = array_map(fn(GroupItem $group) => $group->name, $groups);
        // Grupos que no estan en la api externa pero si en la base de datos
        /** @var Collection<ServerGroup> $groupsToDelete */
        $groupsToDelete = $this->serverGroupRepository->getDiff($groupNames);

        // Iterar sobre los grupos que se van a eliminar y 
        // eliminar sus simbolos y categorias de instrumentos (securities) de la base de datos
        foreach( $groupsToDelete as $group ) {
            $securitiesToDelete = $group->securities;
            $securityIdsToDelete = $securitiesToDelete->pluck('id')->toArray();
            $symbolsIdsToDelete = $securitiesToDelete->flatMap(
                fn(Security $security) => $security->symbols()->pluck('id')
            )->toArray();
            
            $this->symbolRepository->deleteSymbolsByIds($symbolsIdsToDelete);
            $this->securityRepository->deleteSecuritiesByIds($securityIdsToDelete);
        }

        // Inicializar los grupos de instrumentos (securities) y los instrumentos (symbols)
        InitializeGroupJob::dispatch($platformSettingId);
    }
}