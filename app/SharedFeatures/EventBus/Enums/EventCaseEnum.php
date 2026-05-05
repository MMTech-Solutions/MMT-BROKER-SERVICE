<?php

namespace App\SharedFeatures\EventBus\Enums;

enum EventCaseEnum: string
{
    case TRADING_ACCOUNT_CREATED = 'broker.account.created';
    case TRADING_SERVER_SYNC_DONE = 'broker.trading_server.sync_done';
}
