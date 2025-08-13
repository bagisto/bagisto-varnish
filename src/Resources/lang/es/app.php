<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Caché de página completa',
            'info'          => 'Configura los ajustes del caché de página completa para mejorar el rendimiento.',
            'configuration' => [
                'title' => 'Configuración',
                'info'  => 'Gestiona la aplicación de caché y los ajustes relacionados.',
                'fpc'   => [
                    'title'             => 'Aplicación de caché',
                    'info'              => 'Selecciona la aplicación de caché que usarás para tu tienda.',
                    'cache_application' => [
                        'title'   => 'Aplicación de caché',
                        'varnish' => [
                            'title'       => 'Varnish (Recomendado)',
                            'info'        => 'Activa el caché de Varnish para mejorar el rendimiento.',
                            'access_list' => [
                                'title' => 'Lista de acceso',
                                'info'  => 'Direcciones IP separadas por comas permitidas para acceder al servidor Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL del host Varnish',
                                'info'  => 'Introduce la URL o IP de tu servidor Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL del host backend',
                                'info'  => 'Introduce la IP o el nombre del host del servidor backend al que Varnish se conecta (p. ej., localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Puerto del host backend',
                                'info'  => 'Introduce el puerto en el que está funcionando el servidor backend (por defecto: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Periodo de gracia',
                                'info'  => 'Define cuánto tiempo Varnish sirve contenido obsoleto cuando el backend es lento o no está disponible.',
                            ],
                            'export_vcl' => [
                                'title' => 'Exportar VCL (6.0)',
                                'info'  => 'Guarda el archivo localmente o en /etc/varnish/default.vcl. Luego reinicia el servicio de Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Gestión de caché',
                'info'        => 'Gestiona y elimina contenido cacheado.',
                'purge_cache' => [
                    'title'       => 'Purgar caché',
                    'info'        => 'Limpia la caché de tu tienda.',
                    'via_url'     => [
                        'title'        => 'Purgar por URLs',
                        'btn'          => 'Purgar',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Introduce URLs completas separadas por comas para limpiar entradas específicas de caché en Varnish. La ruta y el dominio deben coincidir exactamente.',
                        'confirmation' => '¿Estás seguro de que quieres purgar la caché para las URLs especificadas?',
                    ],
                    'success'         => 'Caché purgada correctamente para: :urls',
                    'failure'         => 'Error al purgar caché para: :urls',
                    'all_success'     => 'Toda la caché purgada correctamente.',
                    'partial_failure' => 'Error al purgar las siguientes URLs: :urls',
                    'exception'       => 'Ocurrió una excepción: :message',
                    'url_required'    => 'Se requiere URL para purgar.',
                    'unknown_url'     => 'URL desconocida',
                ],
                'purge_full_cache' => [
                    'title'        => 'Purgar todo',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Purgar todo',
                    'info'         => 'Esto limpiará todas las entradas de caché de Varnish. Usa con precaución ya que puede afectar temporalmente el rendimiento.',
                    'confirmation' => '¿Estás seguro de que quieres purgar toda la caché? Esto eliminará todo el contenido cacheado y puede afectar temporalmente el rendimiento.',
                ],
            ],
        ],
    ],
];
