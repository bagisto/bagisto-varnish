<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Tam Sayfa Önbelleği',
            'info'          => 'Performansı artırmak için tam sayfa önbellek ayarlarını yapılandırın.',
            'configuration' => [
                'title' => 'Yapılandırma',
                'info'  => 'Önbellek uygulamasını ve ilgili ayarları yönetin.',
                'fpc'   => [
                    'title'             => 'Önbellek Uygulaması',
                    'info'              => 'Mağazanızda kullanmak üzere önbellek uygulamasını seçin.',
                    'cache_application' => [
                        'title'   => 'Önbellek Uygulaması',
                        'varnish' => [
                            'title'       => 'Varnish (Önerilen)',
                            'info'        => 'Performansı artırmak için Varnish önbelleklemesini etkinleştirin.',
                            'access_list' => [
                                'title' => 'Erişim Listesi',
                                'info'  => 'Varnish sunucusuna erişmesine izin verilen, virgülle ayrılmış IP adresleri.',
                            ],
                            'url' => [
                                'title' => 'Varnish Sunucu URL\'si',
                                'info'  => 'Varnish sunucunuzun URL\'sini veya IP adresini girin.',
                            ],
                            'backend_url' => [
                                'title' => 'Arka Uç Sunucu URL\'si',
                                'info'  => 'Varnish\'in bağlandığı arka uç sunucusunun IP adresini veya ana bilgisayar adını girin (örneğin, localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Arka Uç Sunucu Portu',
                                'info'  => 'Arka uç sunucusunun çalıştığı port numarasını girin (varsayılan: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Bekleme Süresi',
                                'info'  => 'Arka uç yavaş veya erişilemez olduğunda Varnish\'in eski içerikleri ne kadar süreyle sunacağını ayarlayın.',
                            ],
                            'export_vcl' => [
                                'title' => 'VCL (6.0) Dışa Aktar',
                                'info'  => 'Dosyayı yerel olarak veya /etc/varnish/default.vcl dizinine kaydedin. Ardından Varnish servisini yeniden başlatın.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Önbellek Yönetimi',
                'info'        => 'Önbelleğe alınmış içeriği yönetin ve temizleyin.',
                'purge_cache' => [
                    'title'       => 'Önbelleği Temizle',
                    'info'        => 'Mağazanızın önbelleğini temizleyin.',
                    'via_url'     => [
                        'title'        => 'URL\'lere Göre Temizle',
                        'btn'          => 'Temizle',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Varnish önbelleğinden belirli kayıtları temizlemek için tam URL\'leri virgülle ayırarak girin. Yol ve alan adı tam olarak eşleşmelidir.',
                        'confirmation' => 'Belirtilen URL\'ler için önbelleği temizlemek istediğinizden emin misiniz?',
                    ],
                    'success'         => ':urls için önbellek başarıyla temizlendi.',
                    'failure'         => ':urls için önbellek temizleme başarısız oldu.',
                    'all_success'     => 'Tüm önbellek başarıyla temizlendi.',
                    'partial_failure' => 'Aşağıdaki URL\'ler için önbellek temizleme başarısız oldu: :urls',
                    'exception'       => 'İstisna oluştu: :message',
                    'url_required'    => 'Temizleme URL\'si gereklidir.',
                    'unknown_url'     => 'Bilinmeyen URL',
                ],
                'purge_full_cache' => [
                    'title'        => 'Tümünü Temizle',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Tümünü Temizle',
                    'info'         => 'Varnish\'den tüm önbellek kayıtlarını temizler. Performansı geçici olarak etkileyebileceği için dikkatli kullanın.',
                    'confirmation' => 'Tüm önbelleği temizlemek istediğinizden emin misiniz? Bu işlem tüm önbelleğe alınmış içeriği kaldıracak ve performansı geçici olarak etkileyebilir.',
                ],
            ],
        ],
    ],
];
