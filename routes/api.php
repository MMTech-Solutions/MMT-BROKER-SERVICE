<?php

use App\Features\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformController;
use Illuminate\Support\Facades\Route;

Route::prefix('broker')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('platforms', ListPlatformsController::class);
        Route::post('platforms', StorePlatformController::class);
        Route::get('platforms/{platformUuid}', ShowPlatformController::class);
        Route::patch('platforms/{platformUuid}', UpdatePlatformController::class);
        Route::delete('platforms/{platformUuid}', DeletePlatformController::class);
    });
});
