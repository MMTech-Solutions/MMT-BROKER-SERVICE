<?php

namespace App\Features\Trading\Account\Services;

use App\Features\Trading\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Trading\Account\UseCases\CreateAccountUseCase;
use App\Features\Trading\TradingServer\Services\FindDefaultServerGroupService;

class CreateDefaultAccountService
{
    public function __construct(
        private readonly FindDefaultServerGroupService $findDefaultServerGroupService,
        private readonly CreateAccountUseCase $createAccountUseCase,
    ) {}

    public function execute(string $userId): void
    {
        $serverGroup = $this->findDefaultServerGroupService->execute();

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
