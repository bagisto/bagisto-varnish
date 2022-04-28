<?php

namespace Webkul\Varnish\Http\Middleware;

use Closure;

class VarnishCache
{
    /**
     * Handle request.
     *
     * @param  mixed  $request
     * @param  Closure  $next
     * @param  int   $cacheTimeInMinutes
     * @return mixed
     */
    public function handle($request, Closure $next, int $cacheTimeInMinutes = 10080)
    {
        $headers = [
            'X-Cacheable'   => 'YES',
            'Cache-Control' => 'public, s-maxage=' . 60 * $cacheTimeInMinutes,
        ];

        if ($request->ajax()) {
            $headers[] = ['X-Ajax' => 'Yes'];
        }

        return $next($request)->withHeaders($headers);
    }
}
