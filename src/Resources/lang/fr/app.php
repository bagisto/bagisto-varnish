<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Cache de page complète',
            'info'          => 'Configurez les paramètres du cache de page complète pour améliorer les performances.',
            'configuration' => [
                'title' => 'Configuration',
                'info'  => 'Gérez l’application de cache et les paramètres associés.',
                'fpc'   => [
                    'title'             => 'Application de cache',
                    'info'              => 'Sélectionnez l’application de cache à utiliser pour votre boutique.',
                    'cache_application' => [
                        'title'   => 'Application de cache',
                        'varnish' => [
                            'title'       => 'Varnish (Recommandé)',
                            'info'        => 'Activez le cache Varnish pour améliorer les performances.',
                            'access_list' => [
                                'title' => 'Liste d’accès',
                                'info'  => 'Adresses IP séparées par des virgules autorisées à accéder au serveur Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL de l’hôte Varnish',
                                'info'  => 'Entrez l’URL ou l’IP de votre serveur Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL de l’hôte backend',
                                'info'  => 'Entrez l’IP ou le nom d’hôte du serveur backend auquel Varnish se connecte (ex. localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Port de l’hôte backend',
                                'info'  => 'Entrez le port sur lequel le serveur backend fonctionne (par défaut : 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Période de grâce',
                                'info'  => 'Définissez la durée pendant laquelle Varnish sert un contenu périmé lorsque le backend est lent ou indisponible.',
                            ],
                            'export_vcl' => [
                                'title' => 'Exporter le VCL (6.0)',
                                'info'  => 'Enregistrez le fichier localement ou dans /etc/varnish/default.vcl. Puis redémarrez le service Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Gestion du cache',
                'info'        => 'Gérez et purge le contenu mis en cache.',
                'purge_cache' => [
                    'title'       => 'Purger le cache',
                    'info'        => 'Videz le cache de votre boutique.',
                    'via_url'     => [
                        'title'        => 'Purger par URLs',
                        'btn'          => 'Purger',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'Entrez les URLs complètes séparées par des virgules pour effacer des entrées spécifiques du cache Varnish. Le chemin et le domaine doivent correspondre exactement.',
                        'confirmation' => 'Êtes-vous sûr de vouloir purger le cache pour les URLs spécifiées ?',
                    ],
                    'success'         => 'Cache purgé avec succès pour : :urls',
                    'failure'         => 'Échec de la purge du cache pour : :urls',
                    'all_success'     => 'Tout le cache a été purgé avec succès.',
                    'partial_failure' => 'Échec de la purge des URLs suivantes : :urls',
                    'exception'       => 'Exception survenue : :message',
                    'url_required'    => 'L’URL de purge est requise.',
                    'unknown_url'     => 'URL inconnue',
                ],
                'purge_full_cache' => [
                    'title'        => 'Tout purger',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'Tout purger',
                    'info'         => 'Cela effacera toutes les entrées du cache Varnish. Utilisez avec précaution car cela peut temporairement affecter les performances.',
                    'confirmation' => 'Êtes-vous sûr de vouloir purger tout le cache ? Cela supprimera tout le contenu mis en cache et peut affecter temporairement les performances.',
                ],
            ],
        ],
    ],
];
