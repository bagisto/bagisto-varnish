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
        'catalog.product.update.after'  => [
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

        'checkout.order.save.after'     => [
            'Webkul\Varnish\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.order.cancel.after'      => [
            'Webkul\Varnish\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.refund.save.after'       => [
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

        'marketing.search_seo.url_rewrites.update.after' => [
            'Webkul\Varnish\Listeners\URLRewrite@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.delete.before' => [
            'Webkul\Varnish\Listeners\URLRewrite@beforeDelete',
        ],
    ];
}
