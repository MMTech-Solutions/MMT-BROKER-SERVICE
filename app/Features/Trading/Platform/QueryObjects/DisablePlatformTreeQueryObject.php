<?php

namespace App\Features\Trading\Platform\QueryObjects;

use App\Features\Trading\Platform\Models\Platform;

class DisablePlatformTreeQueryObject
{
    public function execute(string $platformId): void
    {
        /** @var Platform */
        $platform = Platform::with([
            'tradingServers',
            'tradingServers.serverGroup',
        ])->find($platformId);

        if ($platform) {
            $platform->update([
                'is_active' => false,
            ]);

            foreach ($platform->tradingServers as $tradingServer) {
                $tradingServer->update([
                    'is_active' => false,
                ]);

                $tradingServer->serverGroup()->update([
                    'is_active' => false,
                ]);
            }
        }
    }
}
