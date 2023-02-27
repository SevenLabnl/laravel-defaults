<?php

namespace SevenLab\LaravelDefaults\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogAfterRequest
{
    /**
     * The URIs that should be excluded from logging.
     *
     * @var array
     */
    protected $except = [
        'horizon/*',
        'telescope/*',
        'vendor/*',
        // Custom
        'health',
    ];

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
        if ($this->inExceptArray($request) === false) {
            $requestLog = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'headers' => $request->header(),
            ];

            if (config('laravel-defaults.log_after_request.debug')) {
                $requestLog['data'] = $request->except('password', 'password_confirmation');
            }

            $responseLog = [
                'status' => $response->getStatusCode(),
            ];

            Log::info('LogAfterRequest', [
                'appName' => config('app.name', '?'),
                'userID' => auth()->id(),
                'requestLog' => $requestLog,
                'responseLog' => $responseLog,
            ]);
        }
    }

    /**
     * Determine if the request has a URI that should pass through.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
