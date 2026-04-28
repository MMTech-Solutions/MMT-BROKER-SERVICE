<?php

namespace App\SharedFeatures\EventBus\Publishers\Kafka;

use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;
use App\SharedFeatures\EventBus\Contracts\IntegrationEventInterface;
use Junges\Kafka\Facades\Kafka;


class KafkaIntegrationPublisher implements IntegrationPublisherInterface
{
    public function publish(IntegrationEventInterface $event): void
    {
        Kafka::publish()
            ->onTopic($event->topic())
            ->withKafkaKey($event->key())
            ->withBody($event->payload()->toArray())
            ->send();
    }
}