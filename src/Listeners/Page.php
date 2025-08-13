<?php

namespace Webkul\Varnish\Listeners;

use Webkul\CMS\Repositories\PageRepository;
use Webkul\Varnish\Facades\VarnishCache;

class Page
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository) {}

    /**
     * After page update
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function afterUpdate($page)
    {
        VarnishCache::forget('/page/'.$page->url_key);
    }

    /**
     * Before page delete
     *
     * @param  int  $pageId
     * @return void
     */
    public function beforeDelete($pageId)
    {
        $page = $this->pageRepository->find($pageId);

        VarnishCache::forget('/page/'.$page->url_key);
    }
}
