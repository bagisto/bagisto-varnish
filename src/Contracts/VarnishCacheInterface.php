<?php

namespace Webkul\Varnish\Contracts;

class VarnishCacheInterface
{
    public function forget(string|array $uris, array $tags = []): self
    {
        // Implementation for forgetting URLs in Varnish cache
    }
}
