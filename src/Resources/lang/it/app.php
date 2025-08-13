<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Cache a pagina intera',
            'info'          => 'Configura le impostazioni della cache a pagina intera per migliorare le prestazioni.',
            'configuration' => [
                'title' => 'Configurazione',
                'info'  => 'Gestisci l’applicazione della cache e le impostazioni correlate.',
                'fpc'   => [
                    'title'             => 'Applicazione cache',
                    'info'              => 'Seleziona l’applicazione cache da utilizzare per il tuo negozio.',
                    'cache_application' => [
                        'title'   => 'Applicazione cache',
                        'varnish' => [
                            'title'       => 'Varnish (Consigliato)',
                            'info'        => 'Abilita la cache Varnish per migliorare le prestazioni.',
                            'access_list' => [
                                'title' => 'Lista di accesso',
                                'info'  => 'Indirizzi IP separati da virgola autorizzati ad accedere al server Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL host Varnish',
                                'info'  => 'Inserisci l’URL o l’IP del tuo server Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL host backend',
                                'info'  => 'Inserisci l’IP o il nome host del server backend a cui Varnish si connette (es. localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Porta host backend',
                                'info'  => 'Inserisci la porta su cui il server backend è in esecuzione (default: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Periodo di grazia',
                                'info'  => 'Imposta per quanto tempo Varnish serve contenuti obsoleti quando il backend è lento o non disponibile.',
                            ],
                            'export_vcl' => [
                                'title' => 'Esporta VCL (6.0)',
                                'info'  => 'Salva il file localmente o in /etc/varnish/default.vcl. Poi riavvia il servizio Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Gestione cache',
                'info'        => 'Gestisci e svuota i contenuti memorizzati nella cache.',
                'purge_cache' => [
                    'title'       => 'Svuota cache',
                    'info'        => 'Pulisci la cache del tuo negozio.',
                    'via_url'     => [
                        'title'        => 'Svuota per URL',
                        'btn'          => 'Svuota',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Inserisci URL completi separati da virgola per cancellare specifiche voci di cache da Varnish. Il percorso e il dominio devono corrispondere esattamente.',
                        'confirmation' => 'Sei sicuro di voler svuotare la cache per gli URL specificati?',
                    ],
                    'success'         => 'Cache svuotata con successo per: :urls',
                    'failure'         => 'Impossibile svuotare la cache per: :urls',
                    'all_success'     => 'Tutta la cache è stata svuotata con successo.',
                    'partial_failure' => 'Impossibile svuotare i seguenti URL: :urls',
                    'exception'       => 'Si è verificata un’eccezione: :message',
                    'url_required'    => 'L’URL per lo svuotamento è obbligatorio.',
                    'unknown_url'     => 'URL sconosciuto',
                ],
                'purge_full_cache' => [
                    'title'        => 'Svuota tutto',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Svuota tutto',
                    'info'         => 'Cancellerà tutte le voci di cache da Varnish. Usare con cautela in quanto potrebbe temporaneamente influire sulle prestazioni.',
                    'confirmation' => 'Sei sicuro di voler svuotare l’intera cache? Questo rimuoverà tutti i contenuti memorizzati e potrebbe influire temporaneamente sulle prestazioni.',
                ],
            ],
        ],
    ],
];
