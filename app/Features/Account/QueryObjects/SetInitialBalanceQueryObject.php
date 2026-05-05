<?php

namespace App\Features\Account\QueryObjects;

use App\Features\Account\Models\Account;
use App\SharedFeatures\ValueObjects\PositiveMoney;

class SetInitialBalanceQueryObject
{
    public static function execute(string $accountId, PositiveMoney $money) : void
    {
        Account::where([
            'id' => $accountId
        ])->update([
            'initial_deposit' => $money->toFloat(),
        ]);
    }
}