<?php

namespace App\Features\TradingServer\QueryObject;

use App\Features\TradingServer\Models\ServerGroup;

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
