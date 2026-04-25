<?php

use App\Features\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Platform\Http\V1\Controllers\ListAvailablePlatformsController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformController;
use App\Features\Manager\Http\V1\Controllers\ListManagerEnvironmentsController;
use App\Features\Manager\Http\V1\Controllers\ListManagersController;
use App\Features\Manager\Http\V1\Controllers\StoreManagerController;
use App\Features\Manager\Http\V1\Controllers\ShowManagerController;
use App\Features\Manager\Http\V1\Controllers\UpdateManagerController;
use App\Features\Manager\Http\V1\Controllers\DeleteManagerController;
use App\Features\Manager\Http\V1\Controllers\InitializeManagerController;
use App\Features\Manager\Http\V1\Controllers\SynchronizeManagerController;
use App\Features\Manager\Http\V1\Controllers\ListServerGroupsController;
use App\Features\Manager\Http\V1\Controllers\ListSecuritiesController;
use App\Features\Manager\Http\V1\Controllers\ListSymbolsController;
use App\Features\Manager\Http\V1\Controllers\ListServerGroupSecuritiesController;
use App\Features\Manager\Http\V1\Controllers\ListSecuritySymbolsController;
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
        });

        Route::prefix('managers')->group(function(){

            Route::get('environments/availables', ListManagerEnvironmentsController::class);

            Route::get('/', ListManagersController::class);
            Route::post('/', StoreManagerController::class);
            Route::get('{managerUuid}/server-groups', ListServerGroupsController::class);
            Route::get('{managerUuid}/server-groups/{serverGroupUuid}/securities', ListServerGroupSecuritiesController::class);
            Route::get('{managerUuid}/securities/{securityUuid}/symbols', ListSecuritySymbolsController::class);
            Route::get('{managerUuid}/securities', ListSecuritiesController::class);
            Route::get('{managerUuid}/symbols', ListSymbolsController::class);
            Route::get('/{managerUuid}', ShowManagerController::class);
            Route::patch('/{managerUuid}', UpdateManagerController::class);
            Route::delete('/{managerUuid}', DeleteManagerController::class);
            Route::post('{managerUuid}/initialization', InitializeManagerController::class);
            Route::post('{managerUuid}/synchronization', SynchronizeManagerController::class);
        });
    });
});
