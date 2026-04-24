<?php

namespace App\Features\Manager\Http\V1\Commands;

use App\Features\Manager\Http\V1\Requests\ShowManagerRequest;

class ShowManagerCommand
{
    public function __construct(
        public readonly string $managerId,
    ) {}

    public static function fromRequest(ShowManagerRequest $request): self
    {
        return new self(
            managerId: (string) $request->route('managerUuid'),
        );
    }
}
