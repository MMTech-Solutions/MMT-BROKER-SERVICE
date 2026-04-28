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
        // $required = ['x-user']; // ejemplo
        // foreach ($required as $header) {
        //     if (!$request->headers->has($header) || blank($request->header($header))) {
        //         throw new UnauthenticatedException();
        //     }
        // }

        $request->attributes->set('user', User::fromArray([
            'id' => '00000000-0000-0000-0000-000000000000',
            'name' => 'John',
            'lastname' => 'Doe',
            'username' => 'johndoe',
            'full_name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'sex' => 'male',
            'birth' => '1990-01-01',
            'referer' => 'referer',
            'iblink_id' => '00000000-0000-0000-0000-000000000001',
            'ibratio' => '1.0',
            'discountref' => '0',
            'country_id' => 1,
            'country_iso' => 'ES',
            'state' => '1',
            'city' => '1',
            'street' => 'Some street',
            'zipcode' => '1234567890',
            'address' => 'Some address',
            'langs_id' => 1,
            'status' => 1,
            'client' => '1',
            'client_type' => 'user',
            'security2fa' => [],
            'roles' => [],
        ]));
        
        return $next($request);
    }
}
