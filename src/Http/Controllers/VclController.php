<?php

namespace Webkul\Varnish\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Varnish\Facades\VarnishCache;

class VclController extends Controller
{
    /**
     * Export the VCL configuration file.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportVcl()
    {
        $data = [
            'access_list'         => array_map('trim', explode(',', core()->getConfigData('fpc.configuration.fpc.varnish_access_list') ?? 'localhost,127.0.0.1,::1')),
            'backend_url'         => core()->getConfigData('fpc.configuration.fpc.varnish_backend_url') ?? 'localhost',
            'backend_port'        => core()->getConfigData('fpc.configuration.fpc.varnish_backend_port') ?? '8080',
            'grace_period'        => core()->getConfigData('fpc.configuration.fpc.varnish_grace_period') ?? '3d',
        ];

        $vclContent = view('varnish::vcls.default', $data)->render();

        return Response::make($vclContent, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => 'attachment; filename="default.vcl"',
        ]);
    }

    /**
     * Purge cache for the given URL via AJAX request.
     *
     * @return \Illuminate\Http\Response
     */
    public function purgeCache(Request $request)
    {
        $url = $request->input('purge_url');

        if (empty($url)) {
            session()->flash('error', trans('varnish::app.configuration.fpc.cache_management.purge_cache.url_required'));

            return redirect()->back();
        }

        try {
            $result = VarnishCache::forget($url);

            $successUrls = [];
            $failedUrls = [];

            foreach ($result as $res) {
                if (! empty($res['success'])) {
                    $successUrls[] = $res['url'];
                } else {
                    $failedUrls[] = $res['url'];
                }
            }

            if (count($successUrls)) {
                session()->flash(
                    'success',
                    trans('varnish::app.configuration.fpc.cache_management.purge_cache.success', [
                        'urls' => implode(', ', $successUrls),
                    ])
                );
            }

            if (count($failedUrls)) {
                session()->flash(
                    'error',
                    trans('varnish::app.configuration.fpc.cache_management.purge_cache.failure', [
                        'urls' => implode(', ', $failedUrls),
                    ])
                );
            }

        } catch (\Exception $e) {
            session()->flash(
                'error',
                trans('varnish::app.configuration.fpc.cache_management.purge_cache.exception', [
                    'message' => $e->getMessage(),
                ])
            );
        }

        return redirect()->back();
    }

    public function purgeFullCache(Request $request)
    {
        try {
            $result = VarnishCache::forget('.');

            $successUrls = [];
            $failedUrls = [];

            if (is_array($result)) {
                foreach ($result as $res) {
                    if (! empty($res['success'])) {
                        $successUrls[] = $res['url'] ?? trans('varnish::app.configuration.fpc.cache_management.purge_cache.unknown_url');
                    } else {
                        $failedUrls[] = $res['url'] ?? trans('varnish::app.configuration.fpc.cache_management.purge_cache.unknown_url');
                    }
                }
            }

            if (count($successUrls)) {
                session()->flash(
                    'success',
                    trans('varnish::app.configuration.fpc.cache_management.purge_cache.all_success')
                );
            }

            if (count($failedUrls)) {
                session()->flash(
                    'error',
                    trans('varnish::app.configuration.fpc.cache_management.purge_cache.partial_failure', [
                        'urls' => implode(', ', $failedUrls),
                    ])
                );
            }

        } catch (\Exception $e) {
            session()->flash(
                'error',
                trans('varnish::app.configuration.fpc.cache_management.purge_cache.exception', [
                    'message' => $e->getMessage(),
                ])
            );
        }

        return redirect()->back();
    }
}
