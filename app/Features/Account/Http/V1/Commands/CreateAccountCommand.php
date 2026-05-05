<?php

namespace App\Features\Account\Http\V1\Commands;

use App\Features\Account\Http\V1\Requests\CreateAccountRequest;

class CreateAccountCommand
{
    public function __construct(
        public readonly string $serverGroupId,
        public readonly string $leverageId,
        /** Id del registro en base de datos del monto inicial. Solo para cuentas DEMO */
        public readonly ?string $amountId = null
    ) {}

    public static function fromRequest(CreateAccountRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            serverGroupId: $validated['server_group_id'],
            leverageId: $validated['leverage_id'],
            amountId: $validated['amount_id'] ?? null
        );
    }
}
