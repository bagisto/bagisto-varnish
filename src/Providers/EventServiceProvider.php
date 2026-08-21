<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.update.after' => [
            'Webkul\Varnish\Listeners\Product@afterUpdate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\Varnish\Listeners\Product@beforeDelete',
        ],

        'catalog.category.update.after' => [
            'Webkul\Varnish\Listeners\Category@afterUpdate',
        ],

        'catalog.category.delete.before' => [
            'Webkul\Varnish\Listeners\Category@beforeDelete',
        ],

        'customer.review.update.after' => [
            'Webkul\Varnish\Listeners\Review@afterUpdate',
        ],

        'customer.review.delete.before' => [
            'Webkul\Varnish\Listeners\Review@beforeDelete',
        ],

        'checkout.order.save.after' => [
            'Webkul\Varnish\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.order.cancel.after' => [
            'Webkul\Varnish\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.refund.save.after' => [
            'Webkul\Varnish\Listeners\Refund@afterCreate',
        ],

        'cms.page.update.after' => [
            'Webkul\Varnish\Listeners\Page@afterUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\Varnish\Listeners\Page@beforeDelete',
        ],

        'theme_customization.create.after' => [
            'Webkul\Varnish\Listeners\ThemeCustomization@afterCreate',
        ],

        'theme_customization.update.after' => [
            'Webkul\Varnish\Listeners\ThemeCustomization@afterUpdate',
        ],

        'theme_customization.delete.before' => [
            'Webkul\Varnish\Listeners\ThemeCustomization@beforeDelete',
        ],

        /**
         * Appearance, which replaced theme customization in Bagisto 2.4.
         *
         * Only the events that move the storefront are listened for. Editing, reordering and
         * switching a section on or off are all held as drafts that nothing but the uncached
         * preview route draws, and discarding them puts back what is already cached. Publish
         * raises `section.update.after` for each section it releases.
         */
        'section.create.after' => [
            'Webkul\Varnish\Listeners\Appearance@afterCreate',
        ],

        'section.update.after' => [
            'Webkul\Varnish\Listeners\Appearance@afterUpdate',
        ],

        'section.delete.before' => [
            'Webkul\Varnish\Listeners\Appearance@beforeDelete',
        ],

        'core.channel.update.after' => [
            'Webkul\Varnish\Listeners\Appearance@afterChannelUpdate',
        ],

        'marketing.search_seo.url_rewrites.update.after' => [
            'Webkul\Varnish\Listeners\URLRewrite@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.delete.before' => [
            'Webkul\Varnish\Listeners\URLRewrite@beforeDelete',
        ],
    ];
}
