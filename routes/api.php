<?php

use App\Features\Account\Http\V1\Controllers\CreateAccountController;
use App\Features\Platform\Http\V1\Controllers\{
    DeletePlatformController, ListAvailablePlatformsController, ListPlatformsController,
    ShowPlatformController, StorePlatformController, UpdatePlatformController
};
use App\Features\TradingServer\Http\V1\Controllers\{
    ListTradingServerEnvironmentsController, ListTradingServersController, StoreTradingServerController, ShowTradingServerController,
    UpdateTradingServerController, DeleteTradingServerController, InitializeTradingServerController, SynchronizeTradingServerController,
    ListServerGroupsController, ListSecuritiesController, ListSymbolsController, ListServerGroupSecuritiesController, ListSecuritySymbolsController
};
use App\Features\Leverage\Http\V1\Controllers\{
    ListLeveragesController, StoreLeverageController, ShowLeverageController,
    UpdateLeverageController, DeleteLeverageController, SynchronizeLeveragesController,
    ListServerGroupLeveragesController
};
use Illuminate\Support\Facades\Route;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\MT5\ObjectResponses\HierarchyGroupItem;
use Mmt\TradingServiceSdk\Platforms\Shared\Commands\ConnectBrokerCommand;
use Mmt\TradingServiceSdk\Platforms\TradingService;

Route::get('test', function () {

    $connectBrokerCommand = new ConnectBrokerCommand(
        server: '45.94.184.177',
        port: 443,
        platform_type: PlatformEnum::MT5,
        login: '1102',
        password: '5jVhWdF@',
        name: 'Mi broker',
    );

    $tradingService = resolve(TradingService::class);
    $session = $tradingService->connect($connectBrokerCommand);
    $groupHierarchyResult = $session->mt5()->getGroupHierarchy();
    $groupHierarchy = $groupHierarchyResult->getData(HierarchyGroupItem::class);
    dd($groupHierarchy);
});



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
