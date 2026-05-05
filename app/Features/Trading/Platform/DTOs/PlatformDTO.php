<?php

namespace App\Features\Trading\Platform\DTOs;

use App\Features\Trading\Platform\Models\Platform;
use App\SharedFeatures\User\User;
use App\SharedFeatures\User\UserContext;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Spatie\LaravelData\Attributes\AutoLazy;
use Spatie\LaravelData\Attributes\FromContainer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

class PlatformDTO extends Data
{
    public readonly PlatformEnum $type;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $custom_name,
        public readonly int $volume_factor,
        #[AutoLazy]
        public readonly Lazy|bool $is_active
    ) {
        $this->type = PlatformEnum::tryFromString($this->name);
    }
}
