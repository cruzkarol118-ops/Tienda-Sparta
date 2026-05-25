<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        if (in_array('customer', $guards)) {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, $request->expectsJson() ? null : route('customer.login')
            );
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $request->expectsJson() ? null : route('login')
        );
    }
}
