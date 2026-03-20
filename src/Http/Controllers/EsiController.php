<?php

namespace Webkul\Varnish\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Webkul\Admin\Http\Controllers\Controller;

class EsiController extends Controller
{
    /**
     * Load the view associated with the given ESI tag.
     *
     * @return Response
     */
    public function loadView(Request $request)
    {
        $tag = $request->query('tag');

        $esiTags = Config::get('varnish.esi.views');

        if (! array_key_exists($tag, $esiTags)) {
            abort(404, 'Invalid ESI tag.');
        }

        return response()->view($esiTags[$tag]);
    }
}
