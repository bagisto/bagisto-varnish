<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class Product extends \Webkul\FPC\Listeners\Product
{
    /**
     * Handle product update event.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $urls = $this->getForgettableUrls($product);

        VarnishCache::forget($urls);
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

        $urls = $this->getForgettableUrls($product);

        VarnishCache::forget($urls);
    }
}
