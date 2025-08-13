<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Cache Halaman Penuh',
            'info'          => 'Konfigurasikan pengaturan cache halaman penuh untuk meningkatkan kinerja.',
            'configuration' => [
                'title' => 'Konfigurasi',
                'info'  => 'Kelola aplikasi cache dan pengaturan terkait.',
                'fpc'   => [
                    'title'             => 'Aplikasi Cache',
                    'info'              => 'Pilih aplikasi cache yang akan digunakan untuk toko Anda.',
                    'cache_application' => [
                        'title'   => 'Aplikasi Cache',
                        'varnish' => [
                            'title'       => 'Varnish (Direkomendasikan)',
                            'info'        => 'Aktifkan caching Varnish untuk meningkatkan kinerja.',
                            'access_list' => [
                                'title' => 'Daftar Akses',
                                'info'  => 'Alamat IP yang dipisahkan koma yang diizinkan mengakses server Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL Host Varnish',
                                'info'  => 'Masukkan URL atau IP server Varnish Anda.',
                            ],
                            'backend_url' => [
                                'title' => 'URL Host Backend',
                                'info'  => 'Masukkan IP atau nama host server backend yang terhubung dengan Varnish (misal localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Port Host Backend',
                                'info'  => 'Masukkan port tempat server backend berjalan (default: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Periode Grace',
                                'info'  => 'Atur berapa lama Varnish menyajikan konten lama saat backend lambat atau tidak tersedia.',
                            ],
                            'export_vcl' => [
                                'title' => 'Ekspor VCL (6.0)',
                                'info'  => 'Simpan file secara lokal atau di /etc/varnish/default.vcl. Kemudian restart layanan Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Manajemen Cache',
                'info'        => 'Kelola dan hapus konten cache.',
                'purge_cache' => [
                    'title'       => 'Bersihkan Cache',
                    'info'        => 'Bersihkan cache untuk toko Anda.',
                    'via_url'     => [
                        'title'        => 'Bersihkan berdasarkan URL',
                        'btn'          => 'Bersihkan',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Masukkan URL lengkap yang dipisahkan koma untuk menghapus entri cache tertentu dari Varnish. Path dan domain harus cocok persis.',
                        'confirmation' => 'Apakah Anda yakin ingin membersihkan cache untuk URL yang ditentukan?',
                    ],
                    'success'         => 'Cache berhasil dibersihkan untuk: :urls',
                    'failure'         => 'Gagal membersihkan cache untuk: :urls',
                    'all_success'     => 'Semua cache berhasil dibersihkan.',
                    'partial_failure' => 'Gagal membersihkan URL berikut: :urls',
                    'exception'       => 'Terjadi pengecualian: :message',
                    'url_required'    => 'URL untuk pembersihan diperlukan.',
                    'unknown_url'     => 'URL tidak dikenal',
                ],
                'purge_full_cache' => [
                    'title'        => 'Bersihkan Semua',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Bersihkan Semua',
                    'info'         => 'Ini akan menghapus semua entri cache dari Varnish. Gunakan dengan hati-hati karena dapat memengaruhi kinerja sementara.',
                    'confirmation' => 'Apakah Anda yakin ingin membersihkan seluruh cache? Ini akan menghapus semua konten cache dan dapat memengaruhi kinerja sementara.',
                ],
            ],
        ],
    ],
];
