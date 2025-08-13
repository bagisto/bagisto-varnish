<?php

namespace Webkul\Varnish\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Webkul\Admin\Http\Controllers\Controller;

class EsiController extends Controller
{
    public function loadView(Request $request)
    {
        $tag = $request->query('tag');

        $esiTags = Config::get('varnish.esi');

        if (! array_key_exists($tag, $esiTags)) {
            abort(404, 'Invalid ESI tag.');
        }

        return response()->view($esiTags[$tag]);
    }
}
