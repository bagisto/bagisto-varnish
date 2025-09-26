<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Varnish\Services\VarnishCache;
use Webkul\Varnish\Services\VarnishClient;
use Webkul\Varnish\Contracts\VarnishCacheInterface;
use Webkul\Varnish\Contracts\VarnishClientInterface;
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

        $this->app->singleton(VarnishCacheInterface::class, VarnishCache::class);

        $this->app->singleton('varnishcache', function ($app) {
            return $app->make(VarnishCacheInterface::class);
        });

        $this->app->singleton(VarnishClientInterface::class, VarnishClient::class);

        $this->app->singleton('VarnishClient', function ($app) {
            return $app->make(VarnishClientInterface::class);
        });

        $aliases = config('varnish.aliases', []);
        foreach ($aliases as $alias => $class) {
            if (! class_exists($alias)) {
                \Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
            }
        }

        $this->app->register(EventServiceProvider::class);

        Event::listen('bagisto.shop.layout.body.before', function($viewRenderEventManager) {
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
            __DIR__.'/../Resources/views/shop/components/layouts/header/desktop/bottom.blade.php' => resource_path('themes/default/views/components/layouts/header/desktop/bottom.blade.php'),
            __DIR__.'/../Resources/views/shop/components/layouts/header/mobile/index.blade.php'   => resource_path('themes/default/views/components/layouts/header/mobile/index.blade.php'),
            __DIR__.'/../Resources/views/shop/components/products/card.blade.php'                 => resource_path('themes/default/views/components/products/card.blade.php'),
        ]);
    }
}
