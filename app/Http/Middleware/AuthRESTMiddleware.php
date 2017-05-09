<?php

namespace App\Http\Middleware;
use \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

use Closure;

class AuthRESTMiddleware extends AuthenticateWithBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $this->($guard)->basic() ?: $next($request);
    }
}
