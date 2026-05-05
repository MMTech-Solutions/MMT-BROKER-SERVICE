<?php

namespace App\Features\Finance\Enums;

enum TransactionTypeEnum: int
{
    case DEPOSIT = 1;
    case WITHDRAWAL = 2;
}
