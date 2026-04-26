<?php

namespace App\Features\Leverage\Http\V1\Commands;

use App\Features\Leverage\Http\V1\Requests\SyncLeveragesRequest;

class SyncLeveragesCommand
{
    public function __construct(
        public readonly string $serverGroupId,
        public readonly array $leverageIds,
    ) {}

    public static function fromRequest(SyncLeveragesRequest $request): self
    {
        /** @var array{leverage_ids: string[]} $validated */
        $validated = $request->validated();

        return new self(
            serverGroupId: $request->route('serverGroupUuid'),
            leverageIds: $validated['leverage_ids'],
        );
    }
}
