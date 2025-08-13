<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Varnish\Contracts\VarnishCacheInterface;
use Webkul\Varnish\Contracts\VarnishClientInterface;
use Webkul\Varnish\Http\Middleware\VarnishCache as VarnishCacheMiddleware;
use Webkul\Varnish\Services\VarnishCache;
use Webkul\Varnish\Services\VarnishClient;

class VarnishServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('cache.response', VarnishCacheMiddleware::class);

        $this->commands([
            \Webkul\Varnish\Console\Commands\FlushVarnishCache::class,
        ]);

        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');

        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/shop/web.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'varnish');

        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Config/varnish.php' => config_path('varnish.php'),
            ], 'config');
        }

        $aliases = config('varnish.aliases', []);
        foreach ($aliases as $alias => $class) {
            if (! class_exists($alias)) {
                \Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
            }
        }

        $this->app->register(EventServiceProvider::class);

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'varnish');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'varnish-esi');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(VarnishCacheInterface::class, VarnishCache::class);

        $this->app->singleton('varnishcache', function ($app) {
            return $app->make(VarnishCacheInterface::class);
        });

        $this->app->singleton(VarnishClientInterface::class, VarnishClient::class);

        $this->app->singleton('VarnishClient', function ($app) {
            return $app->make(VarnishClientInterface::class);
        });

        $this->mergeConfigFrom(__DIR__.'/../Config/varnish.php', 'varnish');

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/esi_views.php', 'varnish.esi'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->publishes([
            __DIR__.'/../Resources/views/shop/components/layouts/header/desktop/bottom.blade.php' => resource_path('themes/default/views/components/layouts/header/desktop/bottom.blade.php'),

            __DIR__.'/../Resources/views/shop/components/layouts/header/mobile/index.blade.php' => resource_path('themes/default/views/components/layouts/header/mobile/index.blade.php'),

            __DIR__.'/../Resources/views/shop/components/products/card.blade.php' => resource_path('themes/default/views/components/products/card.blade.php'),
        ]);
    }
}
