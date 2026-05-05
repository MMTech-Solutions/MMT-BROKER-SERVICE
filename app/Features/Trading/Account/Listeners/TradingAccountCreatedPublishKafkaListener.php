<?php

namespace App\Features\Trading\Account\Listeners;

use App\Features\Trading\Account\Events\TradingAccountCreatedEvent;
use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;
use App\SharedFeatures\EventBus\Enums\EventCaseEnum;
use App\SharedFeatures\EventBus\Factories\EventBusFactory;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationEvent;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationPayload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Escucha el evento local TradingAccountCreatedEvent y publica el evento en Kafka
 */
class TradingAccountCreatedPublishKafkaListener implements ShouldQueue
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
            eventName: EventCaseEnum::TRADING_ACCOUNT_CREATED,
            userId: $event->externalUserId,
            name: $event->userFullName,
            email: $event->userEmail,
            payload: [
                'trader_id' => $event->externalTraderId,
                'password' => $event->password,
                'investor_password' => $event->investorPassword,
            ]
        );

        $this->integrationPublisher->publish(
            new KafkaIntegrationEvent(
                topic: config('kafka.app_topic'),
                key: $event->id,
                payload: $payload,
            ),
        );
    }
}
