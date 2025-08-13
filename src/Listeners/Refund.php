<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class Refund extends Product
{
    /**
     * After refund is created
     *
     * @param  \Webkul\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        foreach ($refund->items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = $this->getForgettableUrls($item->product);

            VarnishCache::forget($urls);
        }
    }
}
