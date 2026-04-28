<?php

namespace App\SharedFeatures\EventBus\Publishers\Kafka;

use App\SharedFeatures\EventBus\Contracts\IntegrationEventPayloadInterface;

class KafkaIntegrationPayload implements IntegrationEventPayloadInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $traderId,
        public readonly string $password,
        public readonly string $investorPassword,
        public readonly string $name,
        public readonly string $email,
    ){}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event_name' => 'broker.account.created',
            "source" => 'broker_service',
            'schema_version' => 'v1',
            'pii_level' => 'low',
            'payload' => [
                'user_id' => $this->userId,
                'trader_id' => $this->traderId,
                'password' => $this->password,
                'investor_password' => $this->investorPassword,
                'name' => $this->name,
                'email' => $this->email,
            ]
        ];
    }
}