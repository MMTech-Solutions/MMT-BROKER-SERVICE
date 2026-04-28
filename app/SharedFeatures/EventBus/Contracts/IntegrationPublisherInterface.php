<?php

namespace App\SharedFeatures\EventBus\Contracts;

interface IntegrationPublisherInterface
{
    public function publish(IntegrationEventInterface $event): void;
}