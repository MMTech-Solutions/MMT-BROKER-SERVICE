<?php

namespace App\Features\TradingServer\Enums;

use Illuminate\Support\Str;

enum EnvironmentEnum: int
{
    case DEMO = 1;
    case LIVE = 2;

    public function label(): string
    {
        return Str::ucfirst(strtolower($this->name));
    }

    public static function serialized(): array
    {
        return array_map(fn(self $enviroment) => [
            'name' => $enviroment->name,
            'label' => $enviroment->label(),
            'value' => $enviroment->value,
        ], self::cases());
    }

    public static function values(): array
    {
        return array_map(fn(self $enviroment) => $enviroment->value, self::cases());
    }
}