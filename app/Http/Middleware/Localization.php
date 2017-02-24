<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Facades\Auth;

class Localization
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
        /*
         * set user specific locale
         */
        if (!is_null($request->user())) {
            if (!is_null($request->user()->locale)) {
                app()->setLocale($request->user()->locale);
            }
        }


        return $next($request);
    }
}
