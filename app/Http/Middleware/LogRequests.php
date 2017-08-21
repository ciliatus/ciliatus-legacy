<?php

namespace App\Http\Middleware;

use App\LogRequest;
use Carbon\Carbon;
use Closure;

class LogRequests
{

    protected $start_time;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (!env('ENABLE_REQUEST_LOGGING', false)) {
            return;
        }

        if (isset($response->original['http_code'])) {
            $http_code = $response->original['http_code'];
        }
        else {
            $http_code = 0;
        }
        LogRequest::create([
            'endpoint' => $request->server('REQUEST_URI'),
            'protocol' => $request->server('SERVER_PROTOCOL'),
            'remote_address' => $request->server('REMOTE_ADDR'),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'referrer' => $request->server('HTTP_REFERER'),
            'method' => $request->server('REQUEST_METHOD'),
            'http_status' => $http_code,
            'duration_ms' => (microtime(true) - LARAVEL_START) * 1000,
            'request_time' => Carbon::createFromTimestamp($request->server('REQUEST_TIME'))
        ]);
    }

}
