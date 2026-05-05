<?php

namespace App\Features\Trading\Account\Http\V1\Commands;

use App\Features\Trading\Account\Http\V1\Requests\ListTradingAccountsRequest;

class ListTradingAccountsCommand
{
    public function __construct(
        public readonly ?string $externalUserId,
        public readonly ?string $externalTraderId,
        public readonly ?string $serverGroupId,
        public readonly ?bool $isActive,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(ListTradingAccountsRequest $request): self
    {
        /** @var array{
         *  external_user_id?: string,
         *  external_trader_id?: string,
         *  server_group_id?: string,
         *  is_active?: bool,
         *  per_page?: int
         * } $validated
         */
        $validated = $request->validated();

        return new self(
            externalUserId: $validated['external_user_id'] ?? null,
            externalTraderId: $validated['external_trader_id'] ?? null,
            serverGroupId: $validated['server_group_id'] ?? null,
            isActive: array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : null,
            perPage: (int) ($validated['per_page'] ?? 15),
        );
    }
}
