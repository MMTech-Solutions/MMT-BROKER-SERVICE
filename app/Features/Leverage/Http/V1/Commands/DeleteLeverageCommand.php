<?php

namespace App\Features\Leverage\Http\V1\Commands;

use App\Features\Leverage\Http\V1\Requests\DeleteLeverageRequest;

class DeleteLeverageCommand
{
    public function __construct(
        public readonly string $leverageId,
    ) {}

    public static function fromRequest(DeleteLeverageRequest $request): self
    {
        return new self(
            leverageId: $request->route('leverageUuid'),
        );
    }
}
