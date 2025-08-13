<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'フルページキャッシュ',
            'info'          => 'パフォーマンス向上のためにフルページキャッシュ設定を構成します。',
            'configuration' => [
                'title' => '設定',
                'info'  => 'キャッシュアプリケーションおよび関連設定を管理します。',
                'fpc'   => [
                    'title'             => 'キャッシュアプリケーション',
                    'info'              => 'ストアで使用するキャッシュアプリケーションを選択してください。',
                    'cache_application' => [
                        'title'   => 'キャッシュアプリケーション',
                        'varnish' => [
                            'title'       => 'Varnish（推奨）',
                            'info'        => 'パフォーマンス向上のためにVarnishキャッシュを有効にします。',
                            'access_list' => [
                                'title' => 'アクセスリスト',
                                'info'  => 'Varnishサーバーへのアクセスを許可するIPアドレスをカンマ区切りで入力してください。',
                            ],
                            'url' => [
                                'title' => 'VarnishホストのURL',
                                'info'  => 'VarnishサーバーのURLまたはIPを入力してください。',
                            ],
                            'backend_url' => [
                                'title' => 'バックエンドホストのURL',
                                'info'  => 'Varnishが接続するバックエンドサーバーのIPまたはホスト名を入力してください（例：localhost）。',
                            ],
                            'backend_port' => [
                                'title' => 'バックエンドホストのポート',
                                'info'  => 'バックエンドサーバーが稼働しているポートを入力してください（デフォルト：8080）。',
                            ],
                            'grace_period' => [
                                'title' => 'グレース期間',
                                'info'  => 'バックエンドが遅いか利用できない場合に、Varnishが古いコンテンツを提供する期間を設定します。',
                            ],
                            'export_vcl' => [
                                'title' => 'VCLのエクスポート（6.0）',
                                'info'  => 'ファイルをローカルまたは /etc/varnish/default.vcl に保存し、その後Varnishサービスを再起動してください。',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'キャッシュ管理',
                'info'        => 'キャッシュされたコンテンツを管理およびパージします。',
                'purge_cache' => [
                    'title'       => 'キャッシュのパージ',
                    'info'        => 'ストアのキャッシュをクリアします。',
                    'via_url'     => [
                        'title'        => 'URLによるパージ',
                        'btn'          => 'パージ',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'カンマ区切りで完全なURLを入力して、Varnishの特定のキャッシュエントリをクリアします。パスとドメインは正確に一致する必要があります。',
                        'confirmation' => '指定されたURLのキャッシュをパージしてもよろしいですか？',
                    ],
                    'success'         => '以下のURLのキャッシュを正常にパージしました: :urls',
                    'failure'         => '以下のURLのキャッシュのパージに失敗しました: :urls',
                    'all_success'     => 'すべてのキャッシュを正常にパージしました。',
                    'partial_failure' => '以下のURLのパージに失敗しました: :urls',
                    'exception'       => '例外が発生しました: :message',
                    'url_required'    => 'パージURLは必須です。',
                    'unknown_url'     => '不明なURL',
                ],
                'purge_full_cache' => [
                    'title'        => 'すべてパージ',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'すべてパージ',
                    'info'         => 'Varnishのすべてのキャッシュエントリをクリアします。パフォーマンスに一時的に影響を与える可能性があるため注意して使用してください。',
                    'confirmation' => 'すべてのキャッシュをパージしてもよろしいですか？ これによりすべてのキャッシュされたコンテンツが削除され、一時的にパフォーマンスに影響を与える可能性があります。',
                ],
            ],
        ],
    ],
];
