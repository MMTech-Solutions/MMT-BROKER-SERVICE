<?php

use App\Features\Account\Http\V1\Controllers\CreateAccountController;
use Illuminate\Support\Facades\Route;
use App\Features\Leverage\Http\V1\Controllers\{
    DeleteLeverageController, ListLeveragesController, ListServerGroupLeveragesController,
    ShowLeverageController, StoreLeverageController, SynchronizeLeveragesController,
    UpdateLeverageController,
};
use App\Features\Platform\Http\V1\Controllers\{
    DeletePlatformController, ListAvailablePlatformsController, ListPlatformsController,
    ShowPlatformController, StorePlatformController, UpdatePlatformController
};
use App\Features\TradingServer\Http\V1\Controllers\{
    DeleteTradingServerController, InitializeTradingServerController, ListSecuritiesController,
    ListSecuritySymbolsController, ListServerGroupsController, ListServerGroupSecuritiesController,
    ListSymbolsController, ListTradingServerEnvironmentsController, ListTradingServersController,
    ShowTradingServerController, StoreTradingServerController, SynchronizeTradingServerController,
    UpdateTradingServerController,
};

Route::prefix('broker')->group(function () {
    Route::middleware('gateway.auth.user')->prefix('v1')->group(function () {

        Route::prefix('platforms')->group(function () {
            Route::get('/', ListPlatformsController::class);
            Route::post('/', StorePlatformController::class);
            Route::get('availables', ListAvailablePlatformsController::class);
            Route::get('/{platformUuid}', ShowPlatformController::class);
            Route::patch('/{platformUuid}', UpdatePlatformController::class);
            Route::delete('/{platformUuid}', DeletePlatformController::class);
        });

        Route::prefix('trading-servers')->group(function () {

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

        Route::prefix('accounts')->group(function () {
            Route::post('/', CreateAccountController::class);
        });
    });
});
