<?php

namespace App\Features\Trading\Account\Services;

use App\Features\Trading\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Trading\Account\UseCases\CreateAccountUseCase;
use App\Features\Trading\TradingServer\Factories\ServerGroupRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\ServerGroupRepositoryInterface;

class CreateDefaultAccountService
{
    private ServerGroupRepositoryInterface $serverGroupRepository;

    public function __construct(
        private readonly ServerGroupRepositoryFactory $serverGroupRepositoryFactory,
        private readonly CreateAccountUseCase $createAccountUseCase,
    ) {
        $this->serverGroupRepository = $serverGroupRepositoryFactory->make();
    }

    public function execute(string $userId): void
    {
        $serverGroup = $this->serverGroupRepository->findDefaultActive();

        if (! $serverGroup) {
            return;
        }

        // TODO
        // Condiciones para crear la cuenta:
        // 2. Si el grupo de servidor tiene IBs el usuario tiene que pertenecer al menos a un IB.
        // 3. Si el grupo de servidor no tiene IBs el usuario puede crearse la cuenta.

        $this->createAccountUseCase->execute(new CreateAccountCommand(
            serverGroupId: $serverGroup->id,
            leverageId: $serverGroup->leverages()->first()?->id ?? 100,
        ));

    }
}
