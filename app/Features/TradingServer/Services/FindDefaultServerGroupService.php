<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\Models\ServerGroup;
use App\Features\TradingServer\QueryObject\FindDefaultServerGroupQueryObject;

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
