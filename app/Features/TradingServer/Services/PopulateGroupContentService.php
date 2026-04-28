<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\SymbolItem;

class PopulateGroupContentService
{
    protected SecurityRepositoryInterface $securityRepository;

    protected ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
    ) {
        $this->securityRepository = $this->securityRepositoryFactory->make();
        $this->serverGroupRepository = $this->serverGroupRepositoryFactory->make();
    }

    /**
     * Puebla el contenido de un ServerGroup (securities y symbols) a partir del grupo jerárquico del servicio externo.
     * Asume que el ServerGroup ya existe en DB y que su contenido previo fue limpiado por el llamador.
     */
    public function execute(
        ServerGroup $serverGroup,
        HierarchyGroupItem $groupItem,
        string $tradingServerId,
    ): void {
        $securityModelsToSync = [];

        foreach ($groupItem->categories as $categoryItem) {
            $securityModel = $this->securityRepository->create($categoryItem->category, $tradingServerId);
            $securityModelsToSync[] = $securityModel;

            $mappedSymbols = $this->mapSymbolsToModelAttributes($tradingServerId, $categoryItem->symbols);
            $symbolsCollection = $this->securityRepository->addSymbols($securityModel, $mappedSymbols);
            $this->securityRepository->syncSymbols($securityModel, $symbolsCollection);
        }

        $this->serverGroupRepository->syncSecurities($serverGroup, collect($securityModelsToSync));
    }

    /**
     * @param  SymbolItem[]  $symbols
     */
    private function mapSymbolsToModelAttributes(string $tradingServerId, array $symbols): array
    {
        return array_map(fn (SymbolItem $symbol) => [
            'name' => $symbol->description,
            'alpha' => $symbol->name,
            'stype' => 0,
            'trading_server_id' => $tradingServerId,
        ], $symbols);
    }
}
