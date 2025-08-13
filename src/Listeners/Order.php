<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class Order extends Product
{
    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        foreach ($order->all_items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = $this->getForgettableUrls($item->product);

            VarnishCache::forget($urls);
        }
    }
}
