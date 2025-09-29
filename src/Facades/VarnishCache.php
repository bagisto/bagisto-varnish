<?php

namespace Webkul\Varnish\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Varnish\Services\VarnishCache as VarnishCacheService;

class VarnishCache extends Facade
{
    /**
     * Get the facade accessor.
     */
    protected static function getFacadeAccessor(): string
    {
        return VarnishCacheService::class;
    }
}
