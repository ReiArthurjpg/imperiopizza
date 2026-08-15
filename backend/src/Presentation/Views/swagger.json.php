<?php
$swagger = [
    "openapi" => "3.0.0",
    "info" => [
        "title" => "Imperial Pizza API (Clean Architecture)",
        "version" => "4.0.0",
        "description" => "API Backend refatorado usando Clean Architecture e DDD."
    ],
    "servers" => [
        ["url" => "http://localhost:8001"]
    ],
    "paths" => [
        "/api/init" => [
            "get" => [
                "tags" => ["Geral"],
                "summary" => "Obter dados iniciais (Operação, Equipe, Configurações)",
                "responses" => [
                    "200" => ["description" => "Sucesso"]
                ]
            ]
        ],
        "/api/dashboard/kpis" => [
            "get" => [
                "tags" => ["Dashboard"],
                "summary" => "Obter KPIs do Dashboard (Comandas e Pizzas Feitas)",
                "parameters" => [
                    [
                        "name" => "start_date",
                        "in" => "query",
                        "required" => false,
                        "schema" => ["type" => "string", "format" => "date"],
                        "example" => "2026-08-01",
                        "description" => "Data inicial do período (YYYY-MM-DD). Se omitido, usa a operação ativa do dia."
                    ],
                    [
                        "name" => "end_date",
                        "in" => "query",
                        "required" => false,
                        "schema" => ["type" => "string", "format" => "date"],
                        "example" => "2026-08-14",
                        "description" => "Data final do período (YYYY-MM-DD). Se omitido, usa a operação ativa do dia."
                    ]
                ],
                "responses" => [
                    "200" => [
                        "description" => "KPIs obtidos com sucesso",
                        "content" => [
                            "application/json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "comandas" => ["type" => "integer", "description" => "Total de comandas no período"],
                                        "pizzas"   => ["type" => "integer", "description" => "Total de pizzas finalizadas (status despacho ou completed)"]
                                    ]
                                ],
                                "example" => ["comandas" => 8, "pizzas" => 6]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        "/api/dashboard/top-montadores-mensal" => [
            "get" => [
                "tags" => ["Dashboard"],
                "summary" => "Obter Top Montadores por Mês e Ano",
                "parameters" => [
                    [
                        "name" => "ano",
                        "in" => "query",
                        "required" => false,
                        "schema" => ["type" => "integer"],
                        "example" => 2026,
                        "description" => "Ano do ranking. Se omitido, usa o ano atual."
                    ],
                    [
                        "name" => "mes",
                        "in" => "query",
                        "required" => false,
                        "schema" => ["type" => "integer"],
                        "example" => 8,
                        "description" => "Mês do ranking (1 a 12). Se omitido, usa o mês atual."
                    ]
                ],
                "responses" => [
                    "200" => [
                        "description" => "Ranking obtido com sucesso"
                    ]
                ]
            ]
        ],
        "/api/dashboard/kpis-dia" => [
            "get" => [
                "tags" => ["Dashboard"],
                "summary" => "KPIs da Operação Ativa do Dia",
                "description" => "Retorna KPIs detalhados da operação que está em andamento agora. Só funciona quando uma operação foi iniciada (status != draft e != completed). Quando inativa, retorna operacao_ativa: false.",
                "responses" => [
                    "200" => [
                        "description" => "KPIs retornados com sucesso",
                        "content" => [
                            "application/json" => [
                                "examples" => [
                                    "operacao_ativa" => [
                                        "summary" => "Com operação ativa",
                                        "value" => [
                                            "operacao_ativa"  => true,
                                            "operacao_id"     => "abc-123",
                                            "operacao_status" => "production_open",
                                            "data"            => "2026-08-14",
                                            "iniciada_em"     => "2026-08-14 18:00:00",
                                            "finalizada_em"   => null,
                                            "kpis" => [
                                                "comandas"    => 12,
                                                "pizzas"      => 36,
                                                "esfihas"     => 5,
                                                "vulcoes"     => 2,
                                                "doces"       => 1,
                                                "em_cozinha"  => 3,
                                                "em_forno"    => 2,
                                                "despachadas" => 7,
                                                "top_montadores" => [
                                                    ["name" => "João", "comandas" => 6, "pizzas" => 18]
                                                ]
                                            ]
                                        ]
                                    ],
                                    "operacao_inativa" => [
                                        "summary" => "Sem operação ativa",
                                        "value" => [
                                            "operacao_ativa" => false,
                                            "status"         => "inativa",
                                            "mensagem"       => "Nenhuma operação ativa no momento."
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        "/api/dashboard/equipe-operacao" => [
            "get" => [
                "tags" => ["Dashboard"],
                "summary" => "Equipe da Operação Ativa",
                "description" => "Retorna a equipe que está escalada na operação em andamento (pega os dados atuais do state.json). Só funciona com operação ativa (status != draft e != completed).",
                "responses" => [
                    "200" => [
                        "description" => "Equipe retornada com sucesso",
                        "content" => [
                            "application/json" => [
                                "examples" => [
                                    "ativa" => [
                                        "summary" => "Com operação ativa",
                                        "value" => [
                                            "operacao_ativa"  => true,
                                            "operacao_id"     => "abc-123",
                                            "operacao_status" => "production_open",
                                            "data"            => "2026-08-14",
                                            "equipe"          => [
                                                ["personId" => "uuid-1", "name" => "João", "role" => "Massa"],
                                                ["personId" => "uuid-2", "name" => "Maria", "role" => "Montagem"]
                                            ]
                                        ]
                                    ],
                                    "inativa" => [
                                        "summary" => "Sem operação ativa",
                                        "value" => [
                                            "operacao_ativa" => false,
                                            "status"         => "inativa",
                                            "mensagem"       => "Nenhuma operação ativa no momento.",
                                            "equipe"         => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        "/api/dashboard/movimentacoes-recentes" => [
            "get" => [
                "tags" => ["Dashboard"],
                "summary" => "Movimentações Recentes da Operação Ativa",
                "description" => "Retorna as últimas comandas atualizadas da operação em andamento. Só funciona com operação ativa (status != draft e != completed). Aceita parâmetro opcional 'limit' (padrão 10, máximo 50).",
                "parameters" => [
                    [
                        "name" => "limit",
                        "in" => "query",
                        "required" => false,
                        "schema" => ["type" => "integer", "minimum" => 1, "maximum" => 50],
                        "example" => 10,
                        "description" => "Quantidade de movimentações a retornar (1-50). Padrão: 10."
                    ]
                ],
                "responses" => [
                    "200" => [
                        "description" => "Movimentações retornadas com sucesso",
                        "content" => [
                            "application/json" => [
                                "examples" => [
                                    "ativa" => [
                                        "summary" => "Com operação ativa",
                                        "value" => [
                                            "operacao_ativa"  => true,
                                            "operacao_id"     => "abc-123",
                                            "operacao_status" => "production_open",
                                            "data"            => "2026-08-14",
                                            "total"           => 2,
                                            "movimentacoes"   => [
                                                [
                                                    "id"             => "uuid-1",
                                                    "numero"         => 3,
                                                    "montador"       => "João",
                                                    "status"         => "cozinha",
                                                    "dispatch_status"=> "aguardando",
                                                    "pizzas"         => 2,
                                                    "esfihas"        => 0,
                                                    "vulcoes"        => 1,
                                                    "doces"          => 0,
                                                    "equivalentes"   => 3,
                                                    "nota"           => "",
                                                    "criada_em"      => "2026-08-14 21:34:00",
                                                    "atualizada_em"  => "2026-08-14 21:34:00",
                                                    "tempo_cozinha"  => "2026-08-14 21:34:00",
                                                    "tempo_forno"    => null,
                                                    "tempo_despacho" => null
                                                ]
                                            ]
                                        ]
                                    ],
                                    "inativa" => [
                                        "summary" => "Sem operação ativa",
                                        "value" => [
                                            "operacao_ativa" => false,
                                            "status"         => "inativa",
                                            "mensagem"       => "Nenhuma operação ativa no momento.",
                                            "movimentacoes"  => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        "/api/sync" => [
            "post" => [
                "tags" => ["Sincronização"],
                "summary" => "Sincronizar estado (Comandas, lotes, etc)",
                "responses" => [
                    "200" => ["description" => "Sucesso"]
                ]
            ]
        ],
        "/api/comandas" => [
            "post" => [
                "tags" => ["Comandas"],
                "summary" => "Criar uma nova comanda",
                "requestBody" => [
                    "required" => true,
                    "content" => [
                        "application/json" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "operacao_id" => ["type" => "integer"],
                                    "number" => ["type" => "integer"],
                                    "pizzas" => ["type" => "integer"]
                                ]
                            ]
                        ]
                    ]
                ],
                "responses" => [
                    "200" => ["description" => "Comanda criada"]
                ]
            ]
        ],
        "/api/comandas/status" => [
            "put" => [
                "tags" => ["Comandas"],
                "summary" => "Atualizar status de uma comanda",
                "responses" => [
                    "200" => ["description" => "Status atualizado"]
                ]
            ]
        ],
        "/api/equipe" => [
            "post" => [
                "tags" => ["Equipe"],
                "summary" => "Sincronizar equipe",
                "responses" => [
                    "200" => ["description" => "Equipe sincronizada"]
                ]
            ]
        ]
    ]
];

echo json_encode($swagger);
