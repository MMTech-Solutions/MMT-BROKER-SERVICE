<?php

namespace App\Features\Trading\TradingServer\QueryObject;

use App\Features\Trading\TradingServer\Models\ServerGroup;

class FindDefaultServerGroupQueryObject
{
    public function execute(): ?ServerGroup
    {
        return ServerGroup::where([
            'is_default' => true,
            'is_active' => true,
        ])->first();
    }
}
