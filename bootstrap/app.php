<?php

use App\SharedFeatures\EventBus\Console\KafkaConsumerCommand;
use App\SharedFeatures\Http\Middleware\ResolveUserFromGatewayHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withEvents(
        discover: [
            __DIR__.'/../app/Features/*/Listeners'
        ]
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'gateway.auth.user' => ResolveUserFromGatewayHeaders::class,
        ]);
    })
    ->withCommands([KafkaConsumerCommand::class])
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (MmtException $e) {
            return $e->render();
        });
    })->create();
