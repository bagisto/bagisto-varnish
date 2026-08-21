<?php

namespace Webkul\Varnish\Services;

use Illuminate\Http\Request;

class VarnishCache
{
    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(private VarnishClient $client)
    {
        $this->client->setVarnishServerUrl($this->getVarnishServerUrl());
    }

    /**
     * The Varnish server a PURGE is sent to.
     *
     * `varnish_url` is Varnish itself. `varnish_backend_url` is the Bagisto host written into
     * the exported VCL, and is only read as a fallback so installs configured before the two
     * were told apart keep purging against whatever they were purging against before.
     */
    public function getVarnishServerUrl(): string
    {
        $url = trim(
            core()->getConfigData('cache_management.varnish.configuration.varnish_url')
            ?: core()->getConfigData('cache_management.varnish.configuration.varnish_backend_url')
            ?: '127.0.0.1'
        );

        return preg_match('~^https?://~i', $url) ? $url : 'http://'.$url;
    }

    /**
     * Get the cache tags for the given request.
     *
     * @param  Request  $request
     * @return array
     */
    public function getCacheTags($request)
    {
        $uri = $request->getBaseUrl().$request->getPathInfo();

        if (
            $request->routeIs('shop.search.index')
            && $request->has('query')
        ) {
            $uri .= '?query='.rawurlencode($request->query('query'));
        }

        return $this->getTags($uri);
    }

    /**
     * Both tags a cached page is stamped with.
     *
     * The plain one is shared by every channel, locale and currency the page was cached
     * under, and is what a purge matches on. The suffixed one identifies the single variant,
     * and is kept so a caller that knows exactly which variant it wants can still reach it.
     */
    public function getTags(string $uri): array
    {
        return array_values(array_unique([
            $this->getUri($uri, false),
            $this->getUri($uri),
        ]));
    }

    /**
     * Get the prefix for the cache keys.
     */
    public function getPrefix()
    {
        return 'bagisto-';
    }

    /**
     * Get the sufix for the cache keys.
     */
    public function getSufix()
    {
        return '-'.core()->getCurrentChannel()->code.'-'.core()->getCurrentLocale()->code.'-'.core()->getCurrentCurrency()->code;
    }

    /**
     * Remove the given URIs from the cache.
     *
     * A URI is dropped in every channel, locale and currency it was cached under. The page
     * changed for all of them, and the request doing the purging is almost always an admin
     * one, whose own channel, locale and currency say nothing about how a shopper's request
     * had the page cached.
     */
    public function forget(string|array $uris, array $tags = []): array
    {
        $uris = is_array($uris) ? $uris : func_get_args();

        $results = [];

        foreach ($this->splitUris($uris) as $uri) {
            if ($uri === '.') {
                $results[] = $this->purgeEverything();

                continue;
            }

            $results[] = $this->send($this->getTagPattern($uri), $this->getUri($uri, false));
        }

        return $results;
    }

    /**
     * Drop everything Varnish is holding, tagged by Bagisto or not.
     */
    public function flush(): array
    {
        return [$this->purgeEverything()];
    }

    /**
     * Get the full URI for the given path.
     *
     * With `$withContext` the current channel, locale and currency are appended, naming one
     * cached variant of the page. Without it the tag names the page itself.
     */
    public function getUri(string $uri, bool $withContext = true): string
    {
        if ($uri === '.') {
            return '.';
        }

        $parsed = parse_url($uri);

        $path = $parsed['path'] ?? '';
        $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

        $trimmedUri = trim($path.$query.$fragment, '/');

        if ($trimmedUri === '') {
            $trimmedUri = 'index';
        }

        return $this->getPrefix().$trimmedUri.($withContext ? $this->getSufix() : '');
    }

    /**
     * The ban expression that matches a page in every variant it was cached under.
     *
     * Tags reach Varnish comma separated in one header and are matched as a substring, so the
     * pattern is anchored on the separators. Left unanchored, purging `bagisto-shirts` would
     * also take out `bagisto-shirts-for-men`.
     */
    public function getTagPattern(string $uri): string
    {
        return '(^|,)'.preg_quote($this->getUri($uri, false)).'(,|$)';
    }

    /**
     * Flatten the given URIs into one list.
     *
     * The admin's purge form is a textarea documented as taking several URLs at once, so a
     * single string can hold more than one.
     */
    protected function splitUris(array $uris): array
    {
        $split = [];

        foreach ($uris as $uri) {
            if (! is_string($uri)) {
                continue;
            }

            foreach (preg_split('/[\s,]+/', $uri) as $part) {
                if ($part !== '') {
                    $split[] = $part;
                }
            }
        }

        return array_values(array_unique($split));
    }

    /**
     * Ban every cached object.
     */
    protected function purgeEverything(): array
    {
        return $this->send('.', '*', true);
    }

    /**
     * Send one ban to Varnish and report how it went.
     */
    protected function send(string $pattern, string $label, bool $purgeAll = false): array
    {
        try {
            $response = $this->client->purge($pattern, $purgeAll);

            $status = $response->getStatusCode();

            return [
                'url' => $label,
                'status' => $status,
                'success' => $status >= 200 && $status < 300,
            ];
        } catch (\Exception $e) {
            return [
                'url' => $label,
                'status' => null,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
