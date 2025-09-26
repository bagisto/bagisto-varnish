<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
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
            \Webkul\Varnish\Console\Commands\FlushVarnishCache::class,
        ]);

        $router->aliasMiddleware('cache.response', VarnishCacheMiddleware::class);
        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/shop/web.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'varnish');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'varnish-esi');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'varnish');

        $this->loadPublishers();

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
}
