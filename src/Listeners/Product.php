<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class Product extends \Webkul\FPC\Listeners\Product
{
    /**
     * Handle product create event.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        VarnishCache::forget($this->getForgettableUrls($product));
    }

    /**
     * Handle product update event.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        VarnishCache::forget($this->getForgettableUrls($product));
    }

    /**
     * Handle product deletion event.
     *
     * @param  int  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        $product = $this->productRepository->find($productId);

        if (! $product) {
            return;
        }

        VarnishCache::forget($this->getForgettableUrls($product));
    }
}
