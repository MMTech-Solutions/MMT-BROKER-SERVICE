<?php

namespace Database\Seeders;

use App\Features\Platform\Enums\PlatformEnviroment;
use App\Features\Platform\Models\Platform;
use App\Features\Platform\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            'MT4' => [
                PlatformEnviroment::DEMO => [
                    'host' => 'demo.mt4.example.com',
                    'port' => 443,
                    'username' => 'mt4_demo_user',
                    'password' => 'mt4_demo_pass',
                    'is_active' => true,
                ],
                PlatformEnviroment::LIVE => [
                    'host' => 'live.mt4.example.com',
                    'port' => 443,
                    'username' => 'mt4_live_user',
                    'password' => 'mt4_live_pass',
                    'is_active' => true,
                ],
            ],
            'MT5' => [
                PlatformEnviroment::DEMO => [
                    'host' => 'demo.mt5.example.com',
                    'port' => 443,
                    'username' => 'mt5_demo_user',
                    'password' => 'mt5_demo_pass',
                    'is_active' => true,
                ],
                PlatformEnviroment::LIVE => [
                    'host' => 'live.mt5.example.com',
                    'port' => 443,
                    'username' => 'mt5_live_user',
                    'password' => 'mt5_live_pass',
                    'is_active' => true,
                ],
            ],
            'TRADELOCKER' => [
                PlatformEnviroment::DEMO => [
                    'host' => 'demo.tradelocker.example.com',
                    'port' => 443,
                    'username' => 'tl_demo_user',
                    'password' => 'tl_demo_pass',
                    'is_active' => true,
                ],
                PlatformEnviroment::LIVE => [
                    'host' => 'live.tradelocker.example.com',
                    'port' => 443,
                    'username' => 'tl_live_user',
                    'password' => 'tl_live_pass',
                    'is_active' => true,
                ],
            ],
        ];

        foreach ($definitions as $code => $environments) {
            $platform = Platform::query()->where('code', $code)->first();

            if ($platform === null) {
                continue;
            }

            foreach ($environments as $environment => $attributes) {
                PlatformSetting::query()->firstOrCreate(
                    [
                        'platform_id' => $platform->id,
                        'enviroment' => $environment->value,
                    ],
                    array_merge($attributes, [
                        'platform_id' => $platform->id,
                        'enviroment' => $environment->value,
                    ])
                );
            }
        }
    }
}
