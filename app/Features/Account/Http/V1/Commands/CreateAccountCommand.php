<?php

namespace App\Features\Account\Http\V1\Commands;

use App\Features\Account\Http\V1\Requests\CreateAccountRequest;

class CreateAccountCommand
{
    public function __construct(
        public readonly string $serverGroupId,
        public readonly string $leverageId
    ) {}

    public static function fromRequest(CreateAccountRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            serverGroupId: $validated['server_group_id'],
            leverageId: $validated['leverage_id']
        );
    }
}