<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Pełna pamięć podręczna strony',
            'info'          => 'Skonfiguruj ustawienia pełnej pamięci podręcznej strony w celu poprawy wydajności.',
            'configuration' => [
                'title' => 'Konfiguracja',
                'info'  => 'Zarządzaj aplikacją pamięci podręcznej i powiązanymi ustawieniami.',
                'fpc'   => [
                    'title'             => 'Aplikacja pamięci podręcznej',
                    'info'              => 'Wybierz aplikację pamięci podręcznej do użycia w swoim sklepie.',
                    'cache_application' => [
                        'title'   => 'Aplikacja pamięci podręcznej',
                        'varnish' => [
                            'title'       => 'Varnish (zalecane)',
                            'info'        => 'Włącz pamięć podręczną Varnish, aby poprawić wydajność.',
                            'access_list' => [
                                'title' => 'Lista dostępu',
                                'info'  => 'Adresy IP oddzielone przecinkami, które mają dostęp do serwera Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL hosta Varnish',
                                'info'  => 'Wprowadź URL lub adres IP swojego serwera Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL hosta backend',
                                'info'  => 'Wprowadź adres IP lub nazwę hosta serwera backend, z którym łączy się Varnish (np. localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Port hosta backend',
                                'info'  => 'Wprowadź port, na którym działa serwer backend (domyślnie: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Okres karencji',
                                'info'  => 'Ustaw, jak długo Varnish będzie serwować przestarzałą zawartość, gdy backend jest wolny lub niedostępny.',
                            ],
                            'export_vcl' => [
                                'title' => 'Eksportuj VCL (6.0)',
                                'info'  => 'Zapisz plik lokalnie lub w /etc/varnish/default.vcl. Następnie uruchom ponownie usługę Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Zarządzanie pamięcią podręczną',
                'info'        => 'Zarządzaj i czyść pamięć podręczną.',
                'purge_cache' => [
                    'title'       => 'Wyczyść pamięć podręczną',
                    'info'        => 'Wyczyść pamięć podręczną swojego sklepu.',
                    'via_url'     => [
                        'title'        => 'Wyczyść według URLi',
                        'btn'          => 'Wyczyść',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Wprowadź pełne URL-e oddzielone przecinkami, aby wyczyścić konkretne wpisy z pamięci podręcznej Varnish. Ścieżka i domena muszą dokładnie się zgadzać.',
                        'confirmation' => 'Czy na pewno chcesz wyczyścić pamięć podręczną dla określonych URLi?',
                    ],
                    'success'         => 'Pamięć podręczna została pomyślnie wyczyszczona dla: :urls',
                    'failure'         => 'Nie udało się wyczyścić pamięci podręcznej dla: :urls',
                    'all_success'     => 'Cała pamięć podręczna została pomyślnie wyczyszczona.',
                    'partial_failure' => 'Nie udało się wyczyścić następujących URLi: :urls',
                    'exception'       => 'Wystąpił wyjątek: :message',
                    'url_required'    => 'Wymagany jest URL do wyczyszczenia.',
                    'unknown_url'     => 'Nieznany URL',
                ],
                'purge_full_cache' => [
                    'title'        => 'Wyczyść wszystko',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Wyczyść wszystko',
                    'info'         => 'Spowoduje to usunięcie wszystkich wpisów z pamięci podręcznej Varnish. Używaj ostrożnie, ponieważ może to tymczasowo wpłynąć na wydajność.',
                    'confirmation' => 'Czy na pewno chcesz wyczyścić całą pamięć podręczną? Usunie to całą zawartość pamięci podręcznej i może tymczasowo wpłynąć na wydajność.',
                ],
            ],
        ],
    ],
];
