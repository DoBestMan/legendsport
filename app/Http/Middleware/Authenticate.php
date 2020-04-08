<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->getRedirection($guards),
        );
    }

    protected function getRedirection(array $guards)
    {
        if (in_array("backstage", $guards, true)) {
            return route('backstage.signin');
        }

        return null;
    }
}
