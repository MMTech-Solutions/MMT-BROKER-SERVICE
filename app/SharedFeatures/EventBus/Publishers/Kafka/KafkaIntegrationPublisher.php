<?php

namespace App\SharedFeatures\EventBus\Publishers\Kafka;

use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;
use App\SharedFeatures\EventBus\Contracts\IntegrationEventInterface;
use Mmtech\Rbac\Kafka\KafkaEventPublisher;


class KafkaIntegrationPublisher implements IntegrationPublisherInterface
{
    public function __construct(
        private readonly KafkaEventPublisher $kafkaEventPublisher
    ){}

    
    public function publish(IntegrationEventInterface $event): void
    {
        $this->kafkaEventPublisher->publish(
            topic: $event->topic(),
            key: $event->key(),
            payload: $event->payload()->toArray(),
        );
    }
}