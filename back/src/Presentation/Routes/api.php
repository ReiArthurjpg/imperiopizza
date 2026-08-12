<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple router for the backend API
if ($uri === '/api/init' && $method === 'GET') {
    // Route to InitDataController
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getInitData();
    exit;
}

if ($uri === '/api/sync' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->syncState();
    exit;
}

if ($uri === '/api/comandas' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->createComanda();
    exit;
}

if ($uri === '/api/comandas/status' && $method === 'PUT') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->updateComandaStatus();
    exit;
}

if ($uri === '/api/equipe' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->syncEquipe();
    exit;
}

// 404 Not Found
http_response_code(404);
echo json_encode(['error' => 'Route not found']);
