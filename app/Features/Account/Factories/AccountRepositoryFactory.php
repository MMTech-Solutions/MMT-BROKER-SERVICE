<?php

namespace App\Features\Account\Factories;

use App\Features\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Features\Account\Repositories\AccountRepository;

class AccountRepositoryFactory
{
    public function make(): AccountRepositoryInterface
    {
        return new AccountRepository();
    }
}