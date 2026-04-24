<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\InitializeManagerRequest;

class InitializeManagerCommand
{
    public function __construct(
        public readonly string $managerId
    ) {}

    public static function fromRequest(InitializeManagerRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            managerId: $validated['manager_id']
        );
    }
}