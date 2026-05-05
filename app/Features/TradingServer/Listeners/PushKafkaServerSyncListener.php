<?php

namespace App\Features\TradingServer\Listeners;

use App\Features\TradingServer\Events\TradingServerSyncDoneEvent;
use App\SharedFeatures\EventBus\Contracts\IntegrationPublisherInterface;
use App\SharedFeatures\EventBus\Enums\EventCaseEnum;
use App\SharedFeatures\EventBus\Factories\EventBusFactory;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationEvent;
use App\SharedFeatures\EventBus\Publishers\Kafka\KafkaIntegrationPayload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PushKafkaServerSyncListener implements ShouldQueue
{
    use InteractsWithQueue;

    private IntegrationPublisherInterface $integrationPublisher;

    public function __construct(private readonly EventBusFactory $eventBusFactory)
    {
        $this->integrationPublisher = $eventBusFactory->make('kafka');
    }

    public function handle(TradingServerSyncDoneEvent $event): void
    {
        $this->integrationPublisher->publish(
            new KafkaIntegrationEvent(
                topic: config('kafka.app_topic'),
                key: $event->platformSettingId,
                payload: new KafkaIntegrationPayload(
                    id: $event->platformSettingId,
                    eventName: EventCaseEnum::TRADING_SERVER_SYNC_DONE,
                    userId: $event->userId,
                    name: $event->userName,
                    email: $event->userEmail,
                    payload: ['platform_setting_id' => $event->platformSettingId],
                ),
            ),
        );
    }
}
