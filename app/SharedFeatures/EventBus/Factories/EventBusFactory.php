<?php

namespace App\SharedFeatures\EventBus\Factories;

use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationPublisher;

class EventBusFactory
{
    /**
     * @param 'kafka' $publisher
     * @return IntegrationPublisherInterface
     * @throws \InvalidArgumentException
     */
    public function make(string $publisher): IntegrationPublisherInterface
    {
        return match ($publisher) {
            'kafka' => resolve(KafkaIntegrationPublisher::class),
            default => throw new \InvalidArgumentException('Publisher not found'),
        };
    }
}