<?php

return [
    'configuration' => [
        'cache-management' => [
            'title' => 'Gestionarea cache-ului',
            'info' => 'Gestionați cache-ul aplicației, ștergeți sau reconstruiți datele stocate în cache pentru configurare, rute, vizualizări și altele.',
        ],

        'varnish' => [
            'title' => 'Varnish',
            'info' => 'Configurați setările cache-ului Varnish pentru performanță îmbunătățită.',

            'configuration' => [
                'title' => 'Configurare',
                'info' => 'Gestionați aplicația de cache și setările aferente.',

                'cache_application' => [
                    'title' => 'Aplicație de cache',

                    'varnish' => [
                        'title' => 'Varnish (Recomandat)',
                        'info' => 'Activați cache-ul Varnish pentru a îmbunătăți performanța.',

                        'access_list' => [
                            'title' => 'Listă de acces',
                            'info' => 'Adrese IP separate prin virgulă care au permisiunea de a accesa serverul Varnish.',
                        ],

                        'url' => [
                            'title' => 'URL gazdă Varnish',
                            'info' => 'Introduceți URL-ul sau IP-ul serverului dumneavoastră Varnish.',
                        ],

                        'backend_url' => [
                            'title' => 'URL gazdă backend',
                            'info' => 'Introduceți IP-ul sau numele de gazdă al serverului backend la care se conectează Varnish (de exemplu, localhost).',
                        ],

                        'backend_port' => [
                            'title' => 'Port gazdă backend',
                            'info' => 'Introduceți portul pe care rulează serverul backend (implicit: 8080).',
                        ],

                        'grace_period' => [
                            'title' => 'Perioadă de grație',
                            'info' => 'Stabiliți cât timp Varnish servește conținut învechit atunci când backend-ul este lent sau indisponibil.',
                        ],

                        'export_vcl' => [
                            'title' => 'Exportă VCL (6.0)',
                            'info' => 'Salvați fișierul local sau în /etc/varnish/default.vcl. Apoi reporniți serviciul Varnish.',
                        ],
                    ],
                ],
            ],

            'purge_cache' => [
                'title' => 'Golire cache',
                'info' => 'Ștergeți cache-ul magazinului dumneavoastră.',

                'via_url' => [
                    'title' => 'Golire după URL-uri',
                    'btn' => 'Golește',
                    'placeholder' => 'https://www.example.com, https://www.example.com/cat.jpg',
                    'info' => 'Introduceți URL-uri complete separate prin virgulă pentru a șterge anumite intrări din cache-ul Varnish. Calea și domeniul trebuie să corespundă exact.',
                    'confirmation' => 'Sigur doriți să goliți cache-ul pentru URL-urile specificate?',
                ],

                'success' => 'Cache golit cu succes pentru: :urls',
                'failure' => 'Golirea cache-ului a eșuat pentru: :urls',
                'all_success' => 'Tot cache-ul a fost golit cu succes.',
                'partial_failure' => 'Golirea cache-ului a eșuat pentru următoarele URL-uri: :urls',
                'exception' => 'A apărut o excepție: :message',
                'url_required' => 'URL-ul pentru golire este obligatoriu.',
                'unknown_url' => 'URL necunoscut',
            ],

            'purge_full_cache' => [
                'title' => 'Golește tot',
                'placeholder' => 'https://www.example.com/cat.jpg/foo/bar',
                'btn' => 'Golește tot',
                'info' => 'Va șterge toate intrările din cache-ul Varnish. Folosiți cu atenție, deoarece performanța poate fi afectată temporar.',
                'confirmation' => 'Sigur doriți să goliți întregul cache? Aceasta va elimina tot conținutul stocat și poate afecta temporar performanța.',
            ],
        ],
    ],
];
