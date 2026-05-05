<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\Models\ServerGroup;
use App\Features\Trading\TradingServer\QueryObject\FindDefaultServerGroupQueryObject;

class FindDefaultServerGroupService
{
    public function __construct(
        private readonly FindDefaultServerGroupQueryObject $findDefaultServerGroupQueryObject,
    ) {}

    public function execute(): ?ServerGroup
    {
        return $this->findDefaultServerGroupQueryObject->execute();
    }
}
