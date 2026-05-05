<?php

use App\Features\Trading\Account\Http\V1\Controllers\CreateAccountController;
use App\Features\Trading\Account\Http\V1\Controllers\ListTradingAccountsController;
use App\Features\Trading\Leverage\Http\V1\Controllers\DeleteLeverageController;
use App\Features\Trading\Leverage\Http\V1\Controllers\ListLeveragesController;
use App\Features\Trading\Leverage\Http\V1\Controllers\ListServerGroupLeveragesController;
use App\Features\Trading\Leverage\Http\V1\Controllers\ShowLeverageController;
use App\Features\Trading\Leverage\Http\V1\Controllers\StoreLeverageController;
use App\Features\Trading\Leverage\Http\V1\Controllers\SynchronizeLeveragesController;
use App\Features\Trading\Leverage\Http\V1\Controllers\UpdateLeverageController;
use App\Features\Trading\Platform\Http\V1\Controllers\DeletePlatformController;
use App\Features\Trading\Platform\Http\V1\Controllers\ListAvailablePlatformsController;
use App\Features\Trading\Platform\Http\V1\Controllers\ListPlatformsController;
use App\Features\Trading\Platform\Http\V1\Controllers\ShowPlatformController;
use App\Features\Trading\Platform\Http\V1\Controllers\StorePlatformController;
use App\Features\Trading\Platform\Http\V1\Controllers\UpdatePlatformController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\DeleteTradingServerController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListSecuritiesController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListSecuritySymbolsController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListServerGroupsController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListServerGroupSecuritiesController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListSymbolsController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListTradingServerEnvironmentsController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ListTradingServersController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\ShowTradingServerController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\StoreTradingServerController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\SynchronizeTradingServerController;
use App\Features\Trading\TradingServer\Http\V1\Controllers\UpdateTradingServerController;
use Illuminate\Support\Facades\Route;

Route::prefix('broker')->group(function () {
    Route::middleware(['rbac.auth.user', 'rbac.bind.gateway.user'])->prefix('v1')->group(function () {

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
            Route::post('{tradingServerUuid}/sync', SynchronizeTradingServerController::class);
        });

        Route::prefix('leverages')->group(function () {
            Route::get('/', ListLeveragesController::class);
            Route::post('/', StoreLeverageController::class);
            Route::get('/{leverageUuid}', ShowLeverageController::class);
            Route::patch('/{leverageUuid}', UpdateLeverageController::class);
            Route::delete('/{leverageUuid}', DeleteLeverageController::class);
        });

        Route::prefix('accounts')->group(function () {
            Route::get('/', ListTradingAccountsController::class);
            Route::post('/', CreateAccountController::class);
        });
    });
});
