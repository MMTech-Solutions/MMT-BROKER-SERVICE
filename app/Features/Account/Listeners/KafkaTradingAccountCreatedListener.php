<?php

namespace App\Features\Account\Listeners;

use App\Features\Account\Events\TradingAccountCreatedEvent;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationEvent;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationPayload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\SharedFeatures\EventBus\Factories\EventBusFactory;
use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;

class KafkaTradingAccountCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    private IntegrationPublisherInterface $integrationPublisher;

    public function __construct(
        private readonly EventBusFactory $eventBusFactory,
    ) {
        $this->integrationPublisher = $eventBusFactory->make('kafka');
    }

    public function handle(TradingAccountCreatedEvent $event): void
    {
        $payload = new KafkaIntegrationPayload(
            id: $event->id,
            userId: $event->externalUserId,
            traderId: $event->externalTraderId,
            password: $event->password,
            investorPassword: $event->investorPassword,
            name: $event->name,
            email: $event->email,
        );

        $this->integrationPublisher->publish(
            new KafkaIntegrationEvent(
                topic: config('kafka.topic'),
                key: $event->id,
                payload: $payload,
            ),
        );
    }
}
