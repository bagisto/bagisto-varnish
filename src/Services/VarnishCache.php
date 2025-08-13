<?php

namespace Webkul\Varnish\Services;

class VarnishCache
{
    public function __construct(private VarnishClient $client)
    {
        $this->client->setVarnishServerUrl(core()->getConfigData('fpc.configuration.fpc.varnish_backend_url') ?? 'http://127.0.0.1');
    }

    /**
     * Get the cache tags for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCacheTags($request)
    {
        $tags = [];

        if (
            $request->routeIs('shop.search.index')
            && $request->has('query')
        ) {
            $tags[] = $this->getUri($request->getBaseUrl().$request->getPathInfo().'?query='.$request->query('query'));
        } else {
            if ($request->getRequestUri()) {
                $tags[] = $this->getUri($request->getBaseUrl().$request->getPathInfo());
            }
        }

        return $tags;
    }

    public function getPrefix()
    {
        return 'bagisto-';
    }

    public function getSufix()
    {
        return '-'.core()->getCurrentChannel()->code.'-'.core()->getCurrentLocale()->code.'-'.core()->getCurrentCurrency()->code;
    }

    public function forget(string|array $uris, array $tags = []): array
    {
        $uris = is_array($uris) ? $uris : func_get_args();
        $results = [];

        foreach ($uris as $uri) {
            $fullUri = $this->getUri($uri);

            try {
                $response = $this->client->purge($fullUri);
                $status = $response->getStatusCode();

                $results[] = [
                    'url'     => $fullUri,
                    'status'  => $status,
                    'success' => $status >= 200 && $status < 300,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'url'     => $fullUri,
                    'status'  => null,
                    'success' => false,
                    'error'   => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    public function getUri(string $uri): string
    {
        if ($uri === '.') {
            return '.';
        }

        $prefix = $this->getPrefix();
        $sufix = $this->getSufix();

        // If full URL is given, parse it
        $parsed = parse_url($uri);

        // Get only the path (and optionally query/fragment)
        $path = $parsed['path'] ?? '';
        $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

        $trimmedUri = trim($path.$query.$fragment, '/');

        if ($trimmedUri === '') {
            $trimmedUri = 'index';
        }

        return $prefix.$trimmedUri.$sufix;
    }
}
