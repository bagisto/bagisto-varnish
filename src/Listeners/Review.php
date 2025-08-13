<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Varnish\Facades\VarnishCache;

class Review
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository) {}

    /**
     * After review is updated
     *
     * @param  \Webkul\Product\Contracts\Review  $review
     * @return void
     */
    public function afterUpdate($review)
    {
        VarnishCache::forget('/'.$review->product->url_key);
    }

    /**
     * Before review is deleted
     *
     * @param  \Webkul\Product\Contracts\Review  $review
     * @return void
     */
    public function beforeDelete($reviewId)
    {
        $review = $this->productReviewRepository->find($reviewId);

        VarnishCache::forget('/'.$review->product->url_key);
    }
}
