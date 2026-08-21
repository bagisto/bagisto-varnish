<?php

$items = [];

/**
 * Register the top-level 'cache_management' section if it doesn't already
 * exist in the core configuration (added in Bagisto > 2.4.0).
 */
$coreConfig = config('core') ?? [];

$hasCacheManagement = collect($coreConfig)->contains(fn ($item) => is_array($item) && ($item['key'] ?? '') === 'cache_management');

if (! $hasCacheManagement) {
    $items[] = [
        'key' => 'cache_management',
        'name' => 'varnish::app.configuration.cache-management.title',
        'info' => 'varnish::app.configuration.cache-management.info',
        'sort' => 7,
    ];
}

return array_merge($items, [
    [
        'key' => 'cache_management.varnish',
        'name' => 'varnish::app.configuration.varnish.title',
        'info' => 'varnish::app.configuration.varnish.info',
        'icon' => 'settings/store.svg',
        'sort' => 3,
    ], [
        'key' => 'cache_management.varnish.configuration',
        'name' => 'varnish::app.configuration.varnish.configuration.title',
        'info' => 'varnish::app.configuration.varnish.configuration.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'cache_application',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.title',
                'type' => 'select',
                'default' => 'bagisto-built-in',
                'options' => [
                    [
                        'title' => 'Bagisto Built-in',
                        'value' => 'bagisto-built-in',
                    ],
                    [
                        'title' => 'Varnish (Recommended)',
                        'value' => 'varnish',
                    ],
                ],
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_access_list',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.access_list.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.access_list.info',
                'type' => 'text',
                'depends' => 'cache_application:varnish',
                'default' => 'localhost',
                'validation' => 'required_if:cache_application,varnish',
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_url',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.url.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.url.info',
                'type' => 'text',
                'depends' => 'cache_application:varnish',
                'default' => 'localhost',
                'validation' => 'required_if:cache_application,varnish',
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_backend_url',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.backend_url.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.backend_url.info',
                'type' => 'text',
                'depends' => 'cache_application:varnish',
                'default' => 'localhost',
                'validation' => 'required_if:cache_application,varnish',
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_backend_port',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.backend_port.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.backend_port.info',
                'type' => 'text',
                'depends' => 'cache_application:varnish',
                'default' => '8080',
                'validation' => 'required_if:cache_application,varnish',
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_grace_period',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.grace_period.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.grace_period.info',
                'type' => 'text',
                'depends' => 'cache_application:varnish',
                'default' => '300',
                'validation' => 'required_if:cache_application,varnish',
                'channel_based' => true,
            ],
            [
                'name' => 'varnish_export_vcl',
                'title' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.export_vcl.title',
                'info' => 'varnish::app.configuration.varnish.configuration.cache_application.varnish.export_vcl.info',
                'type' => 'blade',
                'depends' => 'cache_application:varnish',
                'validation' => 'required_if:cache_application,varnish',
                'path' => 'varnish::admin.configuration.export-vcl',
            ],
        ],
    ],
    [
        'key' => 'cache_management.varnish.purge_cache',
        'name' => 'varnish::app.configuration.varnish.purge_cache.title',
        'info' => 'varnish::app.configuration.varnish.purge_cache.info',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'purge_cache',
                'title' => 'varnish::app.configuration.varnish.purge_cache.title',
                'type' => 'blade',
                'path' => 'varnish::admin.configuration.purge-cache-via-url',
            ],
        ],
    ],
    [
        'key' => 'cache_management.varnish.purge_full_cache',
        'name' => '',
        'info' => '',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'full_purge_cache',
                'title' => 'varnish::app.configuration.varnish.purge_full_cache.title',
                'type' => 'blade',
                'path' => 'varnish::admin.configuration.purge-full-cache',
            ],
        ],
    ],
]);
