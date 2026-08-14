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
                "summary" => "Obter dados iniciais (Operação, Equipe, Configurações)",
                "responses" => [
                    "200" => ["description" => "Sucesso"]
                ]
            ]
        ],
        "/api/sync" => [
            "post" => [
                "summary" => "Sincronizar estado (Comandas, lotes, etc)",
                "responses" => [
                    "200" => ["description" => "Sucesso"]
                ]
            ]
        ],
        "/api/comandas" => [
            "post" => [
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
                "summary" => "Atualizar status de uma comanda",
                "responses" => [
                    "200" => ["description" => "Status atualizado"]
                ]
            ]
        ],
        "/api/equipe" => [
            "post" => [
                "summary" => "Sincronizar equipe",
                "responses" => [
                    "200" => ["description" => "Equipe sincronizada"]
                ]
            ]
        ]
    ]
];

echo json_encode($swagger);
