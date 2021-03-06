<?php

namespace Webkul\Varnish\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class VarnishServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('cacheable', \Webkul\Varnish\Http\Middleware\VarnishCache::class);

        $this->commands([
            \Webkul\Varnish\Console\Commands\FlushVarnishCache::class,
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
