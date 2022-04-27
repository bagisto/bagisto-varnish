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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response->withHeaders([
            'X-Cacheable'   => 'YES',
            'Cache-Control' => 'public, s-maxage=604800',
        ]);
    }
}
