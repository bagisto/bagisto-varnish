<?php

namespace Webkul\Varnish\Facades;

use Illuminate\Support\Facades\Facade;

class VarnishCache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'varnishcache';
    }
}
