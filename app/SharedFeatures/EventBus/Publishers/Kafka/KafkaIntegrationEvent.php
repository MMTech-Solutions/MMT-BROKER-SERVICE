<?php

namespace App\SharedFeatures\EventBus\Publishers\Kafka;

use App\SharedFeatures\EventBus\Contracts\IntegrationEventInterface;
use App\SharedFeatures\EventBus\Contracts\IntegrationEventPayloadInterface;

class KafkaIntegrationEvent implements IntegrationEventInterface
{
    public function __construct(
        public readonly string $topic,
        public readonly string $key,
        public readonly IntegrationEventPayloadInterface $payload,
    ){}

    public function topic(): string
    {
        return $this->topic;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function payload(): IntegrationEventPayloadInterface
    {
        return $this->payload;
    }
}