<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Volledige Pagina Cache',
            'info'          => 'Configureer instellingen voor volledige pagina cache voor verbeterde prestaties.',
            'configuration' => [
                'title' => 'Configuratie',
                'info'  => 'Beheer cache applicatie en gerelateerde instellingen.',
                'fpc'   => [
                    'title'             => 'Cache Applicatie',
                    'info'              => 'Selecteer de cache applicatie die voor je winkel gebruikt wordt.',
                    'cache_application' => [
                        'title'   => 'Cache Applicatie',
                        'varnish' => [
                            'title'       => 'Varnish (Aanbevolen)',
                            'info'        => 'Schakel Varnish caching in om de prestaties te verbeteren.',
                            'access_list' => [
                                'title' => 'Toegangs lijst',
                                'info'  => 'Met komma\'s gescheiden IP-adressen die toegang hebben tot de Varnish-server.',
                            ],
                            'url' => [
                                'title' => 'Varnish Host URL',
                                'info'  => 'Voer de URL of het IP-adres van je Varnish-server in.',
                            ],
                            'backend_url' => [
                                'title' => 'Backend Host URL',
                                'info'  => 'Voer het IP-adres of de hostnaam in van de backend-server waar Varnish verbinding mee maakt (bijv. localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Backend Host Poort',
                                'info'  => 'Voer de poort in waarop de backend-server draait (standaard: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Grace Periode',
                                'info'  => 'Stel in hoe lang Varnish verouderde inhoud serveert wanneer de backend traag is of niet beschikbaar.',
                            ],
                            'export_vcl' => [
                                'title' => 'Exporteer VCL (6.0)',
                                'info'  => 'Sla het bestand lokaal op of in /etc/varnish/default.vcl. Herstart daarna de Varnish-service.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Cache Beheer',
                'info'        => 'Beheer en maak gecachte inhoud leeg.',
                'purge_cache' => [
                    'title'       => 'Cache Leegmaken',
                    'info'        => 'Maak de cache van je winkel leeg.',
                    'via_url'     => [
                        'title'        => 'Leegmaken via URL\'s',
                        'btn'          => 'Leegmaken',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Voer volledige URL\'s gescheiden door komma\'s in om specifieke cache-items van Varnish te verwijderen. Het pad en domein moeten exact overeenkomen.',
                        'confirmation' => 'Weet je zeker dat je de cache voor de opgegeven URL\'s wilt leegmaken?',
                    ],
                    'success'         => 'Cache succesvol geleegd voor: :urls',
                    'failure'         => 'Mislukt om cache te legen voor: :urls',
                    'all_success'     => 'Alle cache succesvol geleegd.',
                    'partial_failure' => 'Mislukt om de volgende URL\'s te legen: :urls',
                    'exception'       => 'Er is een uitzondering opgetreden: :message',
                    'url_required'    => 'Leegmaak-URL is verplicht.',
                    'unknown_url'     => 'Onbekende URL',
                ],
                'purge_full_cache' => [
                    'title'        => 'Alles Leegmaken',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Alles Leegmaken',
                    'info'         => 'Dit zal alle cache-items van Varnish wissen. Gebruik dit voorzichtig omdat dit tijdelijk de prestaties kan beïnvloeden.',
                    'confirmation' => 'Weet je zeker dat je de volledige cache wilt leegmaken? Dit verwijdert alle gecachte inhoud en kan tijdelijk de prestaties beïnvloeden.',
                ],
            ],
        ],
    ],
];
