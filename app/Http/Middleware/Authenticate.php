<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthenticated', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        /*
         * set user specific locale
         */
        if (!is_null(Auth::user()->locale))
            App::setLocale(Auth::user()->locale);


        return $next($request);
    }
}
