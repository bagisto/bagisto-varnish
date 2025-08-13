<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => '全页面缓存',
            'info'          => '配置全页面缓存设置以提升性能。',
            'configuration' => [
                'title' => '配置',
                'info'  => '管理缓存应用及相关设置。',
                'fpc'   => [
                    'title'             => '缓存应用',
                    'info'              => '选择您的商店使用的缓存应用。',
                    'cache_application' => [
                        'title'   => '缓存应用',
                        'varnish' => [
                            'title'       => 'Varnish（推荐）',
                            'info'        => '启用 Varnish 缓存以提升性能。',
                            'access_list' => [
                                'title' => '访问列表',
                                'info'  => '允许访问 Varnish 服务器的 IP 地址，使用逗号分隔。',
                            ],
                            'url' => [
                                'title' => 'Varnish 主机 URL',
                                'info'  => '输入您的 Varnish 服务器的 URL 或 IP。',
                            ],
                            'backend_url' => [
                                'title' => '后端主机 URL',
                                'info'  => '输入 Varnish 连接的后端服务器的 IP 或主机名（例如 localhost）。',
                            ],
                            'backend_port' => [
                                'title' => '后端主机端口',
                                'info'  => '输入后端服务器运行的端口（默认：8080）。',
                            ],
                            'grace_period' => [
                                'title' => '宽限期',
                                'info'  => '设置当后端响应缓慢或不可用时，Varnish 允许提供过期内容的时间长度。',
                            ],
                            'export_vcl' => [
                                'title' => '导出 VCL (6.0)',
                                'info'  => '将文件保存到本地或 /etc/varnish/default.vcl，然后重启 Varnish 服务。',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => '缓存管理',
                'info'        => '管理并清除缓存内容。',
                'purge_cache' => [
                    'title'       => '清除缓存',
                    'info'        => '清除您商店的缓存。',
                    'via_url'     => [
                        'title'        => '通过 URL 清除',
                        'btn'          => '清除',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => '输入完整的 URL，用逗号分隔，以清除 Varnish 中的特定缓存条目。路径和域名必须完全匹配。',
                        'confirmation' => '您确定要清除指定 URL 的缓存吗？',
                    ],
                    'success'         => '成功清除缓存：:urls',
                    'failure'         => '清除缓存失败：:urls',
                    'all_success'     => '所有缓存均已成功清除。',
                    'partial_failure' => '以下 URL 清除失败：:urls',
                    'exception'       => '发生异常：:message',
                    'url_required'    => '需要清除的 URL。',
                    'unknown_url'     => '未知的 URL',
                ],
                'purge_full_cache' => [
                    'title'        => '全部清除',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => '全部清除',
                    'info'         => '这将清除 Varnish 中的所有缓存条目。请谨慎使用，因为这可能暂时影响性能。',
                    'confirmation' => '您确定要清除所有缓存吗？此操作将删除所有缓存内容，并可能暂时影响性能。',
                ],
            ],
        ],
    ],
];
