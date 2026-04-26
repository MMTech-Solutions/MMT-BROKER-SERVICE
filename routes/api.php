<?php

use App\Features\Account\Http\V1\Controllers\CreateAccountController;
use App\Features\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Platform\Http\V1\Controllers\ListAvailablePlatformsController;
use App\Features\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Platform\Http\V1\Controllers\UpdatePlatformController;
use App\Features\TradingServer\Http\V1\Controllers\ListTradingServerEnvironmentsController;
use App\Features\TradingServer\Http\V1\Controllers\ListTradingServersController;
use App\Features\TradingServer\Http\V1\Controllers\StoreTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\ShowTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\UpdateTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\DeleteTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\InitializeTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\SynchronizeTradingServerController;
use App\Features\TradingServer\Http\V1\Controllers\ListServerGroupsController;
use App\Features\TradingServer\Http\V1\Controllers\ListSecuritiesController;
use App\Features\TradingServer\Http\V1\Controllers\ListSymbolsController;
use App\Features\TradingServer\Http\V1\Controllers\ListServerGroupSecuritiesController;
use App\Features\TradingServer\Http\V1\Controllers\ListSecuritySymbolsController;
use App\Features\Leverage\Http\V1\Controllers\ListLeveragesController;
use App\Features\Leverage\Http\V1\Controllers\StoreLeverageController;
use App\Features\Leverage\Http\V1\Controllers\ShowLeverageController;
use App\Features\Leverage\Http\V1\Controllers\UpdateLeverageController;
use App\Features\Leverage\Http\V1\Controllers\DeleteLeverageController;
use App\Features\Leverage\Http\V1\Controllers\SynchronizeLeveragesController;
use App\Features\Leverage\Http\V1\Controllers\ListServerGroupLeveragesController;
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

        Route::prefix('trading-servers')->group(function(){

            Route::get('environments/availables', ListTradingServerEnvironmentsController::class);

            Route::get('/', ListTradingServersController::class);
            Route::post('/', StoreTradingServerController::class);
            Route::get('{tradingServerUuid}/server-groups', ListServerGroupsController::class);
            Route::get('{tradingServerUuid}/server-groups/{serverGroupUuid}/securities', ListServerGroupSecuritiesController::class);

            Route::get('{tradingServerUuid}/server-groups/{serverGroupUuid}/leverages', ListServerGroupLeveragesController::class);
            Route::post('{tradingServerUuid}/server-groups/{serverGroupUuid}/leverages/synchronization', SynchronizeLeveragesController::class);
            Route::get('{tradingServerUuid}/securities/{securityUuid}/symbols', ListSecuritySymbolsController::class);
            Route::get('{tradingServerUuid}/securities', ListSecuritiesController::class);
            Route::get('{tradingServerUuid}/symbols', ListSymbolsController::class);
            Route::get('/{tradingServerUuid}', ShowTradingServerController::class);
            Route::patch('/{tradingServerUuid}', UpdateTradingServerController::class);
            Route::delete('/{tradingServerUuid}', DeleteTradingServerController::class);
            Route::post('{tradingServerUuid}/initialization', InitializeTradingServerController::class);
            Route::post('{tradingServerUuid}/synchronization', SynchronizeTradingServerController::class);
        });

        Route::prefix('leverages')->group(function () {
            Route::get('/', ListLeveragesController::class);
            Route::post('/', StoreLeverageController::class);
            Route::get('/{leverageUuid}', ShowLeverageController::class);
            Route::patch('/{leverageUuid}', UpdateLeverageController::class);
            Route::delete('/{leverageUuid}', DeleteLeverageController::class);
        });

        Route::middleware('gateway.auth.user')->prefix('accounts')->group(function () {
            Route::post('/', CreateAccountController::class);
        });
    });
});
