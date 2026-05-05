<?php

namespace App\SharedFeatures\EventBus\Services;

use App\SharedFeatures\EventBus\Events\ExternalUserCreatedEvent;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class KafkaEventConsumerService
{
    public function __construct() {}

    public function __invoke(ConsumerMessage $message, MessageConsumer $consumer)
    {
        $payload = $message->getBody();

        match ($payload['event_name']) {
            'auth.events.v1.user.created' => ExternalUserCreatedEvent::dispatch($payload['user_id'])
        };
    }
}
