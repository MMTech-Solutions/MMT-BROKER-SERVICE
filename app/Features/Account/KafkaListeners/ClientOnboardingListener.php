<?php

namespace App\Features\Account\KafkaListeners;

use Junges\Kafka\Contracts\ConsumerMessage;
use Mmtech\Rbac\Kafka\Contracts\TopicMessageHandlerInterface;

final class ClientOnboardingListener implements TopicMessageHandlerInterface
{
    public function topic(): string
    {
        return 'auth.events.v1';
    }

    public function handle(ConsumerMessage $message): void
    {
        $payload = $message->getBody();
    }
}