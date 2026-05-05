<?php

namespace App\Providers;

use App\SharedFeatures\Exceptions\UnauthenticatedException;
use App\SharedFeatures\User\UserContext;
use App\SharedFeatures\User\Connectors\GatewayUserConnector;
use Illuminate\Support\ServiceProvider;
use Mmtech\Rbac\Auth\GatewayUser;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(UserContext::class, function ($app) {
            /** @var GatewayUser */
            $gatewayUser = auth()->user() ?? throw new UnauthenticatedException();
            $userConnector = new GatewayUserConnector($gatewayUser);
            return new UserContext($userConnector);
        });
    }
    
    public function boot(): void
    {
        //
    }
}
