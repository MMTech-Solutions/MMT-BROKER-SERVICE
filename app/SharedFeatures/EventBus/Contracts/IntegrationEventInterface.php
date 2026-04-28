<?php

namespace App\SharedFeatures\EventBus\Contracts;

interface IntegrationEventInterface
{
    public function topic(): string;
    public function key(): string;
    public function payload(): IntegrationEventPayloadInterface;
}