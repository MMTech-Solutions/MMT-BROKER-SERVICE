<?php

namespace App\SharedFeatures\Http\Middleware;

use App\SharedFeatures\DTOs\User;
use App\SharedFeatures\Exceptions\UnauthenticatedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveUserFromGatewayHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $required = ['x-user']; // ejemplo
        foreach ($required as $header) {
            if (!$request->headers->has($header) || blank($request->header($header))) {
                throw new UnauthenticatedException();
            }
        }
        
        $request->attributes->set('user', User::fromArray(json_decode($request->header('x-user'), true)));

        return $next($request);
    }
}
