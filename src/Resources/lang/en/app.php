<?php

return [
    'configuration' => [
        'cache-management' => [
            'title' => 'Cache Management',
            'info' => 'Manage application cache, clear or rebuild cached data for configuration, routes, views, and more.',
        ],

        'varnish' => [
            'title' => 'Varnish',
            'info' => 'Configure Varnish cache settings for improved performance.',

            'configuration' => [
                'title' => 'Configuration',
                'info' => 'Manage cache application and related settings.',

                'cache_application' => [
                    'title' => 'Cache Application',

                    'varnish' => [
                        'title' => 'Varnish (Recommended)',
                        'info' => 'Enable Varnish caching to improve performance.',

                        'access_list' => [
                            'title' => 'Access List',
                            'info' => 'Comma-separated IP addresses allowed to access the Varnish server.',
                        ],

                        'url' => [
                            'title' => 'Varnish Host URL',
                            'info' => 'Enter the URL or IP of your Varnish server.',
                        ],

                        'backend_url' => [
                            'title' => 'Backend Host URL',
                            'info' => 'Enter the IP or hostname of the backend server Varnish connects to (e.g., localhost).',
                        ],

                        'backend_port' => [
                            'title' => 'Backend Host Port',
                            'info' => 'Enter the port on which the backend server is running (default: 8080).',
                        ],

                        'grace_period' => [
                            'title' => 'Grace Period',
                            'info' => 'Set how long Varnish serves stale content when the backend is slow or unavailable.',
                        ],

                        'export_vcl' => [
                            'title' => 'Export VCL (6.0)',
                            'info' => 'Save the file locally or to /etc/varnish/default.vcl. Then restart Varnish service.',
                        ],
                    ],
                ],
            ],

            'purge_cache' => [
                'title' => 'Purge Cache',
                'info' => 'Clear the cache for your store.',

                'via_url' => [
                    'title' => 'Purge by URLs',
                    'btn' => 'Purge',
                    'placeholder' => 'https://www.example.com, https://www.example.com/cat.jpg',
                    'info' => 'Enter full URLs separated by commas to clear specific cache entries from Varnish. The path and domain must match exactly.',
                    'confirmation' => 'Are you sure you want to purge the cache for the specified URLs?',
                ],

                'success' => 'Cache purged successfully for: :urls',
                'failure' => 'Failed to purge cache for: :urls',
                'all_success' => 'All cache purged successfully.',
                'partial_failure' => 'Failed to purge the following URLs: :urls',
                'exception' => 'Exception occurred: :message',
                'url_required' => 'Purge URL is required.',
                'unknown_url' => 'Unknown URL',
            ],

            'purge_full_cache' => [
                'title' => 'Purge Everything',
                'placeholder' => 'https://www.example.com/cat.jpg/foo/bar',
                'btn' => 'Purge All',
                'info' => 'It will clear all cache entries from Varnish. Use with caution as it may impact performance temporarily.',
                'confirmation' => 'Are you sure you want to purge the entire cache? This will remove all cached content and may temporarily affect performance.',
            ],
        ],
    ],
];
