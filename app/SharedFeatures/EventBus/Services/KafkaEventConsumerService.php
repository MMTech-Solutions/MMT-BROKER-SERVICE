<?php

namespace App\SharedFeatures\EventBus\Services;

use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class KafkaEventConsumerService
{
    public function __construct()
    {

    }

    public function __invoke(ConsumerMessage $message, MessageConsumer $consumer)
    {
        $payload = $message->getBody();
    }
}