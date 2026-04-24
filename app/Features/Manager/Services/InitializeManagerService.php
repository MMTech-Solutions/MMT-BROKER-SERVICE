<?php

namespace App\Features\Manager\Services;

use App\Features\Manager\Exceptions\TradingServiceException;
use App\Features\Manager\Factories\ServerGroupRepositoryFactory;
use App\Features\Manager\Factories\SecurityRepositoryFactory;
use App\Features\Manager\Factories\SymbolRepositoryFactory;
use App\Features\Manager\Factories\ManagerRepositoryFactory;
use App\Features\Manager\Repositories\Contracts\ManagerRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Manager\Repositories\Contracts\SymbolRepositoryInterface;
use App\SharedFeatures\TradingService\Factories\TradingServiceFactory;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use Mmt\TradingServiceSdk\Platforms\MT5\Commands\ListSymbolsCommand;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\SymbolItem;

class InitializeManagerService
{
    protected ServerGroupRepositoryInterface $serverGroupRepository;
    protected SymbolRepositoryInterface $symbolRepository;
    protected SecurityRepositoryInterface $securityRepository;
    protected ManagerRepositoryInterface $managerRepository;
    protected MT5TradingServiceInterface $tradingService;

    
    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
        private readonly TradingServiceFactory $tradingServiceFactory,
        private readonly ManagerRepositoryFactory $managerRepositoryFactory,
    ) {
        $this->serverGroupRepository = $this->serverGroupRepositoryFactory->make();
        $this->securityRepository = $this->securityRepositoryFactory->make();
        $this->symbolRepository = $this->symbolRepositoryFactory->make();
        $this->managerRepository = $this->managerRepositoryFactory->make();
    }

    public function execute(string $managerId) : void
    {
        $this->tradingService = $this->getTradingServiceSession($managerId);

        $groups = $this->getServerGroups();

        $this->initializeServerGroups($managerId, $groups);
    }

    private function getTradingServiceSession(string $managerId) : MT5TradingServiceInterface
    {
        $manager = $this->managerRepository->findById($managerId);

        return $this->tradingServiceFactory->make(
            $manager->platform->toEnum(),
            $manager->connection_id
        );
    }

    /**
     * @throws TradingServiceException
     * @return GroupItem[]
     */
    private function getServerGroups() : array
    {
        $listGroupsResult = $this->tradingService->listGroups();

        if( !$listGroupsResult->isSuccess() ) {
            throw new TradingServiceException($listGroupsResult->getMessage());
        }

        return $listGroupsResult->getData(GroupItem::class);
    }
    
    /**
     * @param GroupItem[] $groups
     * @throws UniqueConstraintViolationException
     */
    private function initializeServerGroups(string $managerId, array $groups) : void
    {
        foreach($groups as $group) {
            // Grupo de instrumentos (security) a sincronizar con el grupo de servidores (group)
            $securityModelsToSyncWithServerGroup = [];
            // Persistir el server group en la base de datos
            $serverGroupModel = $this->serverGroupRepository->basicCreate($group->name, $managerId);
            // ... obtener instrumentos por categoria
            $symbols = $this->getSymbols($group->name);
            // Recorrer los grupos de instrumentos (security) para ...
            foreach($group->symbols_group as $security) {
                // ... persistir la categoria de instrumentos (security) en la base de datos
                $securityModel = $this->securityRepository->create($security, $managerId);

                $securityModelsToSyncWithServerGroup[] = $securityModel;

                // Filtrar los instrumentos (symbol) que pertenecen al grupo de instrumentos (security)
                $symbolsSecurityRelation = array_filter(
                    $symbols,
                    fn(SymbolItem $symbol) => Str::startsWith($symbol->path, substr($security, 0, -1))
                );

                // Mapear los instrumentos (symbol) a los atributos de la base de datos
                $mappedSymbols = $this->mapSymbolsToModelAttributes($managerId, $symbolsSecurityRelation);
                // Persistir los instrumentos (symbol) en la base de datos
                $symbolsModelCollection = $this->securityRepository->addSymbols(
                    $securityModel,
                    $mappedSymbols
                );
                // Sincronizar los instrumentos (symbol) con el grupo de instrumentos (security)
                $this->securityRepository->syncSymbols(
                    $securityModel,
                    $symbolsModelCollection
                );
            }

            // Sincronizar los instrumentos (security) con el grupo de servidores (server_group)
            $this->serverGroupRepository->syncSecurities(
                $serverGroupModel,
                collect($securityModelsToSyncWithServerGroup)
            );
        }
    }
    
    /**
     * @throws TradingServiceException
     */
    private function getSymbols(string $groupName) : array
    {
        $listSymbolsResult = $this->tradingService->listSymbols(new ListSymbolsCommand(
            groupName: $groupName
        ));

        if( !$listSymbolsResult->isSuccess() ) {
            throw new TradingServiceException($listSymbolsResult->getMessage());
        }

        return $listSymbolsResult->getData(SymbolItem::class);
    }

    /**
     * @param SymbolItem[] $symbols
     */
    private function mapSymbolsToModelAttributes(string $platformSettingId, array $symbols) : array
    {
        return array_map(fn(SymbolItem $symbol)  => [
            'name' => $symbol->description,
            'alpha' => $symbol->name,
            'stype' => 0,
            'platform_setting_id' => $platformSettingId
        ], $symbols);
    }
}