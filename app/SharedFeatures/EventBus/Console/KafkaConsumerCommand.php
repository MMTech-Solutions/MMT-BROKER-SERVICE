<?php

namespace App\SharedFeatures\EventBus\Console;

use App\SharedFeatures\EventBus\Services\KafkaEventConsumerService;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;

class KafkaConsumerCommand extends Command
{
    protected $signature = 'kafka:consume';

    protected $description = 'Consume Kafka events';

    public function handle(
        
    ): void
    {
        $consumer = Kafka::consumer(config('kafka.topics'))
            ->withBrokers(env('KAFKA_BROKERS'))
            ->withConsumerGroupId(env('KAFKA_CONSUMER_GROUP_ID'))
            ->withHandler(new KafkaEventConsumerService())
            ->withAutoCommit()
            ->build();

        $this->info('Kafka consumer started...');
        $consumer->consume();
    }
}