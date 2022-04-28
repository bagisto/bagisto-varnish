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
        $response = $next($request);

        if ($request->ajax()) {
            return $response->withHeaders([
                'X-Pass' => 'Yes',
            ]);
        }

        return $response->withHeaders([
            'Cache-Control' => 'public, s-maxage=' . 60 * $cacheTimeInMinutes,
            'X-Cacheable'   => 'YES',
        ]);
    }
}
