<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Caché de pàgina completa',
            'info'          => 'Configureu la configuració del caché de pàgina completa per millorar el rendiment.',
            'configuration' => [
                'title' => 'Configuració',
                'info'  => 'Gestiona l’aplicació de caché i les configuracions relacionades.',
                'fpc'   => [
                    'title'             => 'Aplicació de caché',
                    'info'              => 'Seleccioneu l’aplicació de caché que voleu utilitzar per a la vostra botiga.',
                    'cache_application' => [
                        'title'   => 'Aplicació de caché',
                        'varnish' => [
                            'title'       => 'Varnish (Recomanat)',
                            'info'        => 'Activeu el caché de Varnish per millorar el rendiment.',
                            'access_list' => [
                                'title' => 'Llista d’accés',
                                'info'  => 'Adreces IP separades per comes que tenen permís per accedir al servidor Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL del servidor Varnish',
                                'info'  => 'Introduïu la URL o IP del vostre servidor Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL del servidor backend',
                                'info'  => 'Introduïu la IP o el nom del host del servidor backend al qual es connecta Varnish (p. ex., localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Port del servidor backend',
                                'info'  => 'Introduïu el port en què s’executa el servidor backend (per defecte: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Període de gràcia',
                                'info'  => 'Configureu quant de temps Varnish serveix contingut caducat quan el backend és lent o no està disponible.',
                            ],
                            'export_vcl' => [
                                'title' => 'Exporta VCL (6.0)',
                                'info'  => 'Deseu el fitxer localment o a /etc/varnish/default.vcl. Després, reinicieu el servei de Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Gestió de caché',
                'info'        => 'Gestiona i neteja el contingut emmagatzemat en caché.',
                'purge_cache' => [
                    'title'       => 'Neteja caché',
                    'info'        => 'Neteja la caché de la vostra botiga.',
                    'via_url'     => [
                        'title'        => 'Neteja per URLs',
                        'btn'          => 'Neteja',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Introduïu URLs completes separades per comes per esborrar entrades específiques de la caché de Varnish. El camí i el domini han de coincidir exactament.',
                        'confirmation' => 'Esteu segur que voleu netejar la caché per a les URLs especificades?',
                    ],
                    'success'         => 'Caché netejada amb èxit per a: :urls',
                    'failure'         => 'No s’ha pogut netejar la caché per a: :urls',
                    'all_success'     => 'Tota la caché s’ha netejat amb èxit.',
                    'partial_failure' => 'No s’ha pogut netejar la següent URL: :urls',
                    'exception'       => 'S’ha produït una excepció: :message',
                    'url_required'    => 'Es requereix una URL per netejar.',
                    'unknown_url'     => 'URL desconeguda',
                ],
                'purge_full_cache' => [
                    'title'        => 'Neteja total',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Neteja tot',
                    'info'         => 'Esborrarà totes les entrades de la caché de Varnish. Utilitzeu amb precaució ja que pot afectar temporalment el rendiment.',
                    'confirmation' => 'Esteu segur que voleu netejar tota la caché? Això eliminarà tot el contingut emmagatzemat en caché i pot afectar temporalment el rendiment.',
                ],
            ],
        ],
    ],
];
