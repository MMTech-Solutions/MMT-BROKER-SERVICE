<?php

namespace App\SharedFeatures\EventBus\Publishers\Kafka;

use App\SharedFeatures\EventBus\Contracts\IntegrationEventPayloadInterface;
use App\SharedFeatures\EventBus\Enums\EventCaseEnum;

class KafkaIntegrationPayload implements IntegrationEventPayloadInterface
{
    public function __construct(
        public readonly string $id,
        public readonly EventCaseEnum $eventName,
        public readonly string $userId,
        public readonly string $name,
        public readonly string $email,
        public readonly array $payload,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event_name' => $this->eventName->value,
            'source' => env('APP_NAME'),
            'schema_version' => 'v1',
            'pii_level' => 'low',
            'payload' => [
                'user_id' => $this->userId,
                'name' => $this->name,
                'email' => $this->email,
                ...$this->payload,
            ],
        ];
    }
}
