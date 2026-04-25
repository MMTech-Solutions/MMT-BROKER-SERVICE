<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Exceptions\TradingServiceException;
use App\Features\TradingServer\Factories\SecurityRepositoryFactory;
use App\Features\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\Repositories\Contracts\SecurityRepositoryInterface;
use App\Features\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;
use Illuminate\Support\Str;
use Mmt\TradingServiceSdk\Platforms\MT5\Commands\ListSymbolsCommand;
use Mmt\TradingServiceSdk\Platforms\MT5\Contracts\MT5TradingServiceInterface;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\GroupItem;
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
     * Puebla el contenido de un ServerGroup (securities y symbols) a partir de la API externa.
     * Asume que el ServerGroup ya existe en DB y que su contenido previo fue limpiado por el llamador.
     *
     * @throws TradingServiceException
     */
    public function execute(
        ServerGroup $serverGroup,
        GroupItem $groupItem,
        string $TradingServerId,
        MT5TradingServiceInterface $tradingService
    ): void {
        $securityModelsToSync = [];

        $symbols = $this->getSymbols($groupItem->name, $tradingService);

        foreach ($groupItem->symbols_group as $security) {
            $securityModel = $this->securityRepository->create($security, $TradingServerId);
            $securityModelsToSync[] = $securityModel;

            $symbolsForSecurity = array_filter(
                $symbols,
                fn(SymbolItem $symbol) => Str::startsWith($symbol->path, substr($security, 0, -1))
            );

            $mappedSymbols = $this->mapSymbolsToModelAttributes($TradingServerId, $symbolsForSecurity);
            $symbolsCollection = $this->securityRepository->addSymbols($securityModel, $mappedSymbols);
            $this->securityRepository->syncSymbols($securityModel, $symbolsCollection);
        }

        $this->serverGroupRepository->syncSecurities($serverGroup, collect($securityModelsToSync));
    }

    /**
     * @throws TradingServiceException
     * @return SymbolItem[]
     */
    private function getSymbols(string $groupName, MT5TradingServiceInterface $tradingService): array
    {
        $result = $tradingService->listSymbols(new ListSymbolsCommand(groupName: $groupName));

        if (!$result->isSuccess()) {
            throw new TradingServiceException($result->getMessage());
        }

        return $result->getData(SymbolItem::class);
    }

    /**
     * @param SymbolItem[] $symbols
     */
    private function mapSymbolsToModelAttributes(string $TradingServerId, array $symbols): array
    {
        return array_map(fn(SymbolItem $symbol) => [
            'name'       => $symbol->description,
            'alpha'      => $symbol->name,
            'stype'      => 0,
            'trading_server_id' => $TradingServerId,
        ], $symbols);
    }
}
