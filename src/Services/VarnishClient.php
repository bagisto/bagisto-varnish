<?php

namespace Webkul\Varnish\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class VarnishClient
{
    /**
     * Seconds to wait on Varnish before giving up.
     *
     * A purge rides along with an admin save, so an unreachable Varnish must not be able to
     * hold that save open.
     */
    public const TIMEOUT = 5;

    /**
     * The Varnish server URL.
     */
    protected string $varnishServerUrl;

    /**
     * Set the Varnish server URL.
     */
    public function setVarnishServerUrl(string $varnishServerUrl): void
    {
        $this->varnishServerUrl = $varnishServerUrl;
    }

    /**
     * Send an HTTP request to the Varnish server.
     *
     * A full purge carries the tags pattern alongside the purge-all header, so that a VCL
     * exported before that header existed still bans everything Bagisto tagged.
     *
     * @return Response
     */
    public function sendRequest(string $method, string $uri, array $options = [])
    {
        $headers = ['X-Bagisto-Tags-Pattern' => $uri];

        if (! empty($options['purge_all'])) {
            $headers['X-Bagisto-Purge-All'] = '1';
        }

        return Http::withOptions(['http_errors' => false])
            ->timeout($options['timeout'] ?? self::TIMEOUT)
            ->withHeaders($headers)
            ->send($method, $this->varnishServerUrl);
    }

    /**
     * Purge a specific URI from Varnish cache.
     *
     * @return Response
     */
    public function purge(string $uri, bool $purgeAll = false)
    {
        return $this->sendRequest('PURGE', $uri, ['purge_all' => $purgeAll]);
    }
}
