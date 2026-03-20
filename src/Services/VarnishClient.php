<?php

namespace Webkul\Varnish\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class VarnishClient
{
    /**
     * The Varnish server URL.
     */
    protected string $varnishServerUrl;

    /**
     * Create a new service instance.
     */
    public function setVarnishServerUrl(string $varnishServerUrl): void
    {
        $this->varnishServerUrl = $varnishServerUrl;
    }

    /**
     * Send an HTTP request to the Varnish server.
     *
     * @return Response
     */
    public function sendRequest(string $method, string $uri, array $options = [])
    {
        return Http::withOptions(['http_errors' => false])
            ->withHeaders([
                'X-Bagisto-Tags-Pattern' => $uri,
            ])
            ->send($method, $this->varnishServerUrl);
    }

    /**
     * Purge a specific URI from Varnish cache.
     *
     * @return Response
     */
    public function purge(string $uri)
    {
        return $this->sendRequest('PURGE', $uri);
    }
}
