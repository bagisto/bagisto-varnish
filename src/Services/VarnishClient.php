<?php

namespace Webkul\Varnish\Services;

class VarnishClient
{
    protected string $varnishServerUrl;

    public function setVarnishServerUrl(string $varnishServerUrl): void
    {
        $this->varnishServerUrl = $varnishServerUrl;
    }

    /**
     * Send an HTTP request to the Varnish server.
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function sendRequest(string $method, string $uri, array $options = [])
    {
        return \Illuminate\Support\Facades\Http::withOptions(['http_errors' => false])
            ->withHeaders([
                'X-Bagisto-Tags-Pattern' => $uri,
            ])
            ->send($method, $this->varnishServerUrl);
    }

    /**
     * Purge a specific URI from Varnish cache.
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function purge(string $uri)
    {
        return $this->sendRequest('PURGE', $uri);
    }
}
