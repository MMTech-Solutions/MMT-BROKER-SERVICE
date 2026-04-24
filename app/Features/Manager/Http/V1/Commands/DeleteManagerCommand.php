<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\DeleteManagerRequest;

class DeleteManagerCommand
{
    public function __construct(
        public readonly string $managerId,
    ) {}

    public static function fromRequest(DeleteManagerRequest $request): self
    {
        return new self(
            managerId: (string) $request->route('managerUuid'),
        );
    }
}
