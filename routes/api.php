<?php

use App\Features\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Platform\Http\V1\Controllers\DeletePlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformSettingsController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('broker')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('platforms', ListPlatformsController::class);
        Route::post('platforms', StorePlatformController::class);
        Route::get('platforms/{platformUuid}', ShowPlatformController::class);
        Route::patch('platforms/{platformUuid}', UpdatePlatformController::class);
        Route::delete('platforms/{platformUuid}', DeletePlatformController::class);

        Route::get('platforms/{platformUuid}/settings', ListPlatformSettingsController::class);
        Route::post('platforms/{platformUuid}/settings', StorePlatformSettingController::class);
        Route::get('platforms/{platformUuid}/settings/{settingUuid}', ShowPlatformSettingController::class);
        Route::patch('platforms/{platformUuid}/settings/{settingUuid}', UpdatePlatformSettingController::class);
        Route::delete('platforms/{platformUuid}/settings/{settingUuid}', DeletePlatformSettingController::class);
    });
});
