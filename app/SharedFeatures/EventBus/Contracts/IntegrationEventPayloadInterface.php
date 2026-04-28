<?php

namespace App\SharedFeatures\EventBus\Contracts;

interface IntegrationEventPayloadInterface
{
    public function toArray(): array;
}