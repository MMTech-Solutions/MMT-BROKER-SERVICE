<?php

namespace Database\Seeders;

use App\Features\Trading\Platform\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'MT4',
                'custom_name' => null,
                'code' => 'MT4',
                'volume_factor' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'MT5',
                'custom_name' => null,
                'code' => 'MT5',
                'volume_factor' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'TradeLocker',
                'custom_name' => null,
                'code' => 'TRADELOCKER',
                'volume_factor' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($platforms as $attributes) {
            Platform::query()->firstOrCreate(
                ['code' => $attributes['code']],
                $attributes
            );
        }
    }
}
