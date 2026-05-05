<?php

namespace App\Features\Trading\Account\Factories;

use App\Features\Trading\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\Trading\Account\Repositories\AccountRepository;

class AccountRepositoryFactory
{
    public function make(): AccountRepositoryInterface
    {
        return new AccountRepository();
    }
}