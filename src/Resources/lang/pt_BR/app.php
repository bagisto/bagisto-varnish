<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'Cache de Página Completa',
            'info'          => 'Configure as configurações do cache de página completa para melhorar o desempenho.',
            'configuration' => [
                'title' => 'Configuração',
                'info'  => 'Gerencie a aplicação de cache e configurações relacionadas.',
                'fpc'   => [
                    'title'             => 'Aplicação de Cache',
                    'info'              => 'Selecione a aplicação de cache a ser usada para sua loja.',
                    'cache_application' => [
                        'title'   => 'Aplicação de Cache',
                        'varnish' => [
                            'title'       => 'Varnish (Recomendado)',
                            'info'        => 'Ative o cache Varnish para melhorar o desempenho.',
                            'access_list' => [
                                'title' => 'Lista de Acesso',
                                'info'  => 'Endereços IP separados por vírgula permitidos para acessar o servidor Varnish.',
                            ],
                            'url' => [
                                'title' => 'URL do Host Varnish',
                                'info'  => 'Insira a URL ou IP do seu servidor Varnish.',
                            ],
                            'backend_url' => [
                                'title' => 'URL do Host Backend',
                                'info'  => 'Insira o IP ou nome do host do servidor backend ao qual o Varnish se conecta (ex: localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'Porta do Host Backend',
                                'info'  => 'Insira a porta na qual o servidor backend está rodando (padrão: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'Período de Graça',
                                'info'  => 'Defina por quanto tempo o Varnish servirá conteúdo stale quando o backend estiver lento ou indisponível.',
                            ],
                            'export_vcl' => [
                                'title' => 'Exportar VCL (6.0)',
                                'info'  => 'Salve o arquivo localmente ou em /etc/varnish/default.vcl. Depois reinicie o serviço Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'Gerenciamento de Cache',
                'info'        => 'Gerencie e limpe conteúdo em cache.',
                'purge_cache' => [
                    'title'       => 'Limpar Cache',
                    'info'        => 'Limpe o cache da sua loja.',
                    'via_url'     => [
                        'title'        => 'Limpar por URLs',
                        'btn'          => 'Limpar',
                        'placeholder'  => 'https://www.exemplo.com, https://www.exemplo.com/cat.jpg',
                        'info'         => 'Digite URLs completas separadas por vírgulas para limpar entradas específicas do cache do Varnish. O caminho e domínio devem coincidir exatamente.',
                        'confirmation' => 'Tem certeza que deseja limpar o cache para as URLs especificadas?',
                    ],
                    'success'         => 'Cache limpo com sucesso para: :urls',
                    'failure'         => 'Falha ao limpar cache para: :urls',
                    'all_success'     => 'Todo o cache foi limpo com sucesso.',
                    'partial_failure' => 'Falha ao limpar as seguintes URLs: :urls',
                    'exception'       => 'Exceção ocorrida: :message',
                    'url_required'    => 'URL para limpar é obrigatório.',
                    'unknown_url'     => 'URL desconhecida',
                ],
                'purge_full_cache' => [
                    'title'        => 'Limpar Tudo',
                    'placeholder'  => 'https://www.exemplo.com/cat.jpg/foo/bar',
                    'btn'          => 'Limpar Tudo',
                    'info'         => 'Isso limpará todas as entradas do cache do Varnish. Use com cautela, pois pode afetar temporariamente o desempenho.',
                    'confirmation' => 'Tem certeza que deseja limpar todo o cache? Isso removerá todo o conteúdo em cache e pode afetar temporariamente o desempenho.',
                ],
            ],
        ],
    ],
];
