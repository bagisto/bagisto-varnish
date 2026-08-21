<?php

namespace Webkul\Varnish\Http\Middleware;

use Closure;
use Webkul\Varnish\Facades\VarnishCache as VarnishCacheFacade;

class VarnishCache
{
    /**
     * Handle request.
     *
     * The lifetime is offered to Varnish through `s-maxage` alone, while browsers are asked to
     * revalidate. A purge reaches only the shared cache, so a browser handed the same lifetime
     * would keep a copy that nothing could invalidate.
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

        $response->headers->set('Cache-Control', 'public, s-maxage='.(60 * $cacheTimeInMinutes).', max-age=0, must-revalidate');

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
                $tag = trim($tag);

                if ($tag === '') {
                    continue;
                }

                $tags[] = $tag;
                $tags[] = $tag.VarnishCacheFacade::getSufix();
            }
        }

        return implode(',', array_unique($tags));
    }
}
