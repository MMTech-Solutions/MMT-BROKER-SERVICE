<?php

use App\Features\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Platform\Http\V1\Controllers\DeletePlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\ListAvailablePlatformsController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformSettingsController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformSettingController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformSettingEnvironmentsController;
use Illuminate\Support\Facades\Route;

Route::prefix('broker')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::prefix('platforms')->group(function () {
            Route::get('/', ListPlatformsController::class);
            Route::post('/', StorePlatformController::class);
            Route::get('availables', ListAvailablePlatformsController::class);
            Route::get('/{platformUuid}', ShowPlatformController::class);
            Route::patch('/{platformUuid}', UpdatePlatformController::class);
            Route::delete('/{platformUuid}', DeletePlatformController::class);

            Route::get('environments/availables', ListPlatformSettingEnvironmentsController::class);

            Route::prefix('{platformUuid}/settings')->group(function () {
                Route::get('/', ListPlatformSettingsController::class);
                Route::post('/', StorePlatformSettingController::class);
                Route::get('/{settingUuid}', ShowPlatformSettingController::class);
                Route::patch('/{settingUuid}', UpdatePlatformSettingController::class);
                Route::delete('/{settingUuid}', DeletePlatformSettingController::class);
            });
        });
    });
});
