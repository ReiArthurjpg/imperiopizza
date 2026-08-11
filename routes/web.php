<?php

// Dashboard / Painel do Operador
$router->get('/', 'HomeController@index');

// PDV & Caixa
$router->get('/pdv', 'PdvController@index');

// Comandas
$router->get('/comandas', 'ComandaController@index');

// Cozinha
$router->get('/cozinha', 'CozinhaController@index');

// Estoque
$router->get('/estoque', 'EstoqueController@index');

// Configurações
$router->get('/config', 'ConfigController@index');

// Equipe
$router->get('/equipe', 'EquipeController@index');

// Relatórios
$router->get('/relatorios', 'RelatoriosController@index');

// API
$router->get('/api/init', 'ApiController@getInitData');
$router->post('/api/sync', 'ApiController@syncState');
$router->post('/api/comandas', 'ApiController@createComanda');
$router->put('/api/comandas/status', 'ApiController@updateComandaStatus');
$router->post('/api/equipe', 'ApiController@syncEquipe');
