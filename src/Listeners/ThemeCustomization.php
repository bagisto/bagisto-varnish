<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Webkul\Varnish\Facades\VarnishCache;

class ThemeCustomization
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository) {}

    /**
     * After theme customization create
     *
     * @param  \Webkul\Shop\Contracts\ThemeCustomization  $themeCustomization
     * @return void
     */
    public function afterCreate($themeCustomization)
    {
        VarnishCache::forget('/');
    }

    /**
     * After theme customization update
     *
     * @param  \Webkul\Shop\Contracts\ThemeCustomization  $themeCustomization
     * @return void
     */
    public function afterUpdate($themeCustomization)
    {
        VarnishCache::forget('/');
    }

    /**
     * Before theme customization delete
     *
     * @param  int  $themeCustomizationId
     * @return void
     */
    public function beforeDelete($themeCustomizationId)
    {
        VarnishCache::forget('/');
    }
}
