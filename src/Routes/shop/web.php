<?php

use Illuminate\Support\Facades\Route;
use Webkul\Varnish\Http\Controllers\EsiController;

Route::get('/esi', [EsiController::class, 'loadView'])->name('varnish.esi.load');

Route::get('/flashes', function () {
    $types = ['success', 'warning', 'error', 'info'];
    $flashes = [];

    foreach ($types as $type) {
        if (session()->has($type)) {
            $flashes[] = [
                'type'    => $type,
                'message' => session($type),
            ];
        }
    }

    return response()->json($flashes);
})->name('varnish.session.flashes');
