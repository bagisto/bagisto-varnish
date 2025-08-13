<?php

use Illuminate\Support\Facades\Route;
use Webkul\Varnish\Http\Controllers\VclController;

Route::controller(VclController::class)->prefix('config/varnish')->group(function () {
    Route::get('vcl/export', 'exportVcl')->name('varnish.configuration.vcl.export');
});

Route::controller(VclController::class)->prefix('config/varnish')->group(function () {
    Route::post('cache/purge', 'purgeCache')->name('varnish.configuration.cache.purge');
});

Route::controller(VclController::class)->prefix('config/varnish')->group(function () {
    Route::post('cache/full-purge', 'purgeFullCache')->name('varnish.configuration.full.cache.purge');
});
