<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Varnish\Facades\VarnishCache;

class URLRewrite
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected URLRewriteRepository $urlRewriteRepository) {}

    /**
     * After URL Rewrite update
     *
     * @param  \Webkul\Marketing\Contracts\URLRewrite  $urlRewrite
     * @return void
     */
    public function afterUpdate($urlRewrite)
    {
        VarnishCache::forget('/'.$urlRewrite->request_path);
    }

    /**
     * Before URL Rewrite delete
     *
     * @param  int  $urlRewriteId
     * @return void
     */
    public function beforeDelete($urlRewriteId)
    {
        $urlRewrite = $this->urlRewriteRepository->find($urlRewriteId);

        VarnishCache::forget('/'.$urlRewrite->request_path);
    }
}
