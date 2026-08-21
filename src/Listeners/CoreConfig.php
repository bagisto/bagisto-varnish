<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class CoreConfig
{
    /**
     * Handle core configuration save event.
     *
     * A configuration change is not confined to one page. Currency, tax, channel and theme
     * settings all reach the whole storefront, so the cache is dropped in full.
     *
     * @return void
     */
    public function afterUpdate()
    {
        VarnishCache::flush();
    }
}
