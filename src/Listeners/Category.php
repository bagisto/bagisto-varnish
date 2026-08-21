<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Varnish\Facades\VarnishCache;

class Category extends \Webkul\FPC\Listeners\Category
{
    /**
     * Handle category create event.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterCreate($category)
    {
        VarnishCache::forget($this->homePath());
    }

    /**
     * Handle category update event.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        VarnishCache::forget($this->forgettablePaths($category));
    }

    /**
     * Handle category deletion event.
     *
     * @param  int  $categoryId
     * @return void
     */
    public function beforeDelete($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        if (! $category) {
            return;
        }

        VarnishCache::forget($this->forgettablePaths($category));
    }
}
