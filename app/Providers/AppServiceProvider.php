<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\SharedFeatures\Application\UserContext;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(UserContext::class, function ($app) {
            $user = $app['request']->attributes->get('user');
            return new UserContext($user);
        });
    }
    
    public function boot(): void
    {
        //
    }
}
