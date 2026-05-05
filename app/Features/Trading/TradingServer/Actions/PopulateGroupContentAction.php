<?php

namespace App\Features\Trading\TradingServer\Actions;

use App\Features\Trading\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Factories\SymbolRepositoryFactory;
use App\Features\Trading\TradingServer\Models\Security;
use App\Features\Trading\TradingServer\Models\ServerGroup;
use App\Features\Trading\TradingServer\Models\Symbol;
use App\Features\Trading\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use App\Features\Trading\TradingServer\Repositories\Contracts\SymbolRepositoryInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\SymbolItem;

class PopulateGroupContentAction
{
    protected SecurityRepositoryInterface $securityRepository;

    protected ServerGroupRepositoryInterface $serverGroupRepository;

    protected SymbolRepositoryInterface $symbolRepository;

    public function __construct(
        private readonly SecurityRepositoryFactory $securityRepositoryFactory,
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly SymbolRepositoryFactory $symbolRepositoryFactory,
    ) {
        $this->securityRepository = $this->securityRepositoryFactory->make();
        $this->serverGroupRepository = $this->serverGroupRepositoryFactory->make();
        $this->symbolRepository = $this->symbolRepositoryFactory->make();
    }

    /**
     * Alinea securities y symbols del ServerGroup con el grupo jerárquico del servicio externo
     * (reutiliza filas existentes por nombre de categoría y servidor, sin vaciar el grupo antes).
     */
    public function execute(
        ServerGroup $serverGroup,
        HierarchyGroupItem $groupItem,
        string $tradingServerId,
    ): void {
        $serverGroup->loadMissing('securities');
        $previousSecurityIds = $serverGroup->securities->pluck('id')->all();

        $securityModelsToSync = [];

        foreach ($groupItem->categories as $categoryItem) {
            $securityModel = $this->securityRepository->updateOrCreateForTradingServer(
                $categoryItem->category,
                $tradingServerId,
            );
            $securityModelsToSync[] = $securityModel;

            $mappedSymbols = $this->mapSymbolsToModelAttributes($tradingServerId, $categoryItem->symbols);
            $this->securityRepository->addSymbols($securityModel, $mappedSymbols);
        }

        $this->serverGroupRepository->syncSecurities(
            $serverGroup,
            collect($securityModelsToSync)->unique('id')->values(),
        );

        $newSecurityIds = collect($securityModelsToSync)->pluck('id')->unique()->all();
        $removedSecurityIds = array_diff($previousSecurityIds, $newSecurityIds);

        $this->pruneDetachedSecurities($removedSecurityIds);
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

    /**
     * Elimina securities que ya no pertenecen a este grupo y no están enlazadas a ningún otro;
     * borra symbols que queden sin ninguna security.
     *
     * @param  array<int, string>  $removedSecurityIds
     */
    private function pruneDetachedSecurities(array $removedSecurityIds): void
    {
        foreach ($removedSecurityIds as $securityId) {
            $security = Security::query()->find($securityId);
            if ($security === null) {
                continue;
            }
            if ($security->serverGroups()->exists()) {
                continue;
            }

            $symbolIds = $security->symbols()->pluck('id')->all();
            $this->securityRepository->deleteSecuritiesByIds([$securityId]);

            foreach ($symbolIds as $symbolId) {
                $stillLinked = Symbol::query()
                    ->whereKey($symbolId)
                    ->whereHas('securities')
                    ->exists();

                if (! $stillLinked) {
                    $this->symbolRepository->deleteSymbolsByIds([$symbolId]);
                }
            }
        }
    }
}
