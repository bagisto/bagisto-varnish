<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Varnish\Console\Commands\FlushVarnishCache;
use Webkul\Varnish\Http\Middleware\VarnishCache as VarnishCacheMiddleware;

class VarnishServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/varnish.php',
            'varnish'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router)
    {
        $this->commands([
            FlushVarnishCache::class,
        ]);

        $router->aliasMiddleware('cache.response', VarnishCacheMiddleware::class);
        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/admin/web.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/shop/web.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'varnish');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/shop/components', 'varnish');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'varnish');

        $this->loadPublishers();

        $this->registerAcl();

        $this->app->register(EventServiceProvider::class);

        Event::listen('bagisto.shop.layout.body.before', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('varnish::shop.view-render-events.customer-status');
        });
    }

    /**
     * Load the publishers.
     */
    public function loadPublishers(): void
    {
        $this->publishes([
            __DIR__.'/../Config/varnish.php' => config_path('varnish.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../publishables/views/shop' => resource_path('themes/default/views'),
        ]);
    }

    /**
     * Grant the admin routes through the configuration permission.
     *
     * Bagisto's Bouncer refuses an admin route that no ACL entry maps whenever the role has
     * custom permissions, so without this such a role gets a 401 on purge and VCL export.
     */
    public function registerAcl(): void
    {
        $routes = [
            'varnish.configuration.vcl.export',
            'varnish.configuration.cache.purge',
            'varnish.configuration.full.cache.purge',
        ];

        config(['acl' => collect(config('acl', []))
            ->map(fn ($item) => ($item['key'] ?? null) === 'configuration'
                ? array_merge($item, ['route' => array_values(array_unique([...(array) $item['route'], ...$routes]))])
                : $item)
            ->all()]);
    }
}
