<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Vollseiten-Cache',
            'info'          => 'Konfigurieren Sie die Einstellungen für den Vollseiten-Cache zur Verbesserung der Leistung.',
            'configuration' => [
                'title' => 'Konfiguration',
                'info'  => 'Verwalten Sie die Cache-Anwendung und zugehörige Einstellungen.',
                'fpc'   => [
                    'title'             => 'Cache-Anwendung',
                    'info'              => 'Wählen Sie die Cache-Anwendung aus, die für Ihren Shop verwendet werden soll.',
                    'cache_application' => [
                        'title'   => 'Cache-Anwendung',
                        'varnish' => [
                            'title'       => 'Varnish (Empfohlen)',
                            'info'        => 'Aktivieren Sie Varnish-Caching zur Leistungsverbesserung.',
                            'access_list' => [
                                'title' => 'Zugriffsliste',
                                'info'  => 'Komma-getrennte IP-Adressen, die Zugriff auf den Varnish-Server haben.',
                            ],
                            'url' => [
                                'title' => 'Varnish-Host-URL',
                                'info'  => 'Geben Sie die URL oder IP Ihres Varnish-Servers ein.',
                            ],
                            'backend_url' => [
                                'title' => 'Backend-Host-URL',
                                'info'  => 'Geben Sie die IP oder den Hostnamen des Backend-Servers ein, mit dem Varnish verbunden ist (z. B. localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Backend-Host-Port',
                                'info'  => 'Geben Sie den Port ein, auf dem der Backend-Server läuft (Standard: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Kulanzzeit',
                                'info'  => 'Legen Sie fest, wie lange Varnish veraltete Inhalte bereitstellt, wenn das Backend langsam oder nicht verfügbar ist.',
                            ],
                            'export_vcl' => [
                                'title' => 'VCL exportieren (6.0)',
                                'info'  => 'Speichern Sie die Datei lokal oder unter /etc/varnish/default.vcl. Starten Sie anschließend den Varnish-Dienst neu.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Cache-Verwaltung',
                'info'        => 'Verwalten und bereinigen Sie zwischengespeicherte Inhalte.',
                'purge_cache' => [
                    'title'       => 'Cache bereinigen',
                    'info'        => 'Leeren Sie den Cache für Ihren Shop.',
                    'via_url'     => [
                        'title'        => 'Nach URLs bereinigen',
                        'btn'          => 'Bereinigen',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Geben Sie vollständige URLs durch Kommas getrennt ein, um bestimmte Cache-Einträge aus Varnish zu löschen. Pfad und Domain müssen genau übereinstimmen.',
                        'confirmation' => 'Sind Sie sicher, dass Sie den Cache für die angegebenen URLs bereinigen möchten?',
                    ],
                    'success'         => 'Cache erfolgreich bereinigt für: :urls',
                    'failure'         => 'Cache-Bereinigung fehlgeschlagen für: :urls',
                    'all_success'     => 'Alle Caches wurden erfolgreich bereinigt.',
                    'partial_failure' => 'Fehler beim Bereinigen der folgenden URLs: :urls',
                    'exception'       => 'Ausnahme aufgetreten: :message',
                    'url_required'    => 'Purge-URL ist erforderlich.',
                    'unknown_url'     => 'Unbekannte URL',
                ],
                'purge_full_cache' => [
                    'title'        => 'Alles bereinigen',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Alles bereinigen',
                    'info'         => 'Dies löscht alle Cache-Einträge von Varnish. Verwenden Sie es mit Vorsicht, da dies die Leistung vorübergehend beeinträchtigen kann.',
                    'confirmation' => 'Sind Sie sicher, dass Sie den gesamten Cache bereinigen möchten? Dadurch werden alle zwischengespeicherten Inhalte entfernt und die Leistung kann vorübergehend beeinträchtigt werden.',
                ],
            ],
        ],
    ],
];
