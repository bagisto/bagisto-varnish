<?php

namespace Webkul\Varnish\Http\Middleware;

use Closure;
use Webkul\Varnish\Facades\VarnishCache as VarnishCacheFacade;

class VarnishCache
{
    /**
     * Handle request.
     *
     * @param  mixed  $request
     * @return mixed
     */
    public function handle($request, Closure $next, int $cacheTimeInMinutes = 10080)
    {
        $response = $next($request);

        $tags = $this->generateBagistoTags($request, $response);

        if ($tags) {
            $response->headers->set('X-Bagisto-Tags', $tags);
        }

        $response->headers->set('X-Cacheable', 'YES');
        $response->headers->set('Cache-Control', 'public, max-age='. 60 * $cacheTimeInMinutes.', s-maxage='. 60 * $cacheTimeInMinutes);

        if ($request->ajax()) {
            $response->headers->set('X-Ajax', 'Yes');
        }

        return $response;
    }

    /**
     * Generate bagisto tags.
     *
     * @param  mixed  $request
     * @param  mixed  $response
     * @return string
     */
    protected function generateBagistoTags($request, $response)
    {
        $tags = VarnishCacheFacade::getCacheTags($request);

        if ($response->headers->has('X-Bagisto-Tag')) {
            $responseTags = explode(',', $response->headers->get('X-Bagisto-Tag'));

            foreach ($responseTags as $tag) {
                $tags[] = VarnishCacheFacade::getSufix().trim($tag);
            }
        }

        return implode(',', $tags);
    }
}
