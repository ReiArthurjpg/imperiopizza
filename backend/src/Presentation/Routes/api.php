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

if ($uri === '/api/dashboard/kpis' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getDashboardKpis();
    exit;
}

if ($uri === '/api/dashboard/top-montadores-mensal' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getTopAssemblersMensal();
    exit;
}

if ($uri === '/api/dashboard/kpis-dia' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getKpisDia();
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

// Swagger UI
if ($uri === '/' || $uri === '/docs') {
    require_once __DIR__ . '/../Views/swagger.php';
    exit;
}

if ($uri === '/api/swagger.json') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/../Views/swagger.json.php';
    exit;
}

// 404 Not Found
http_response_code(404);
echo json_encode(['error' => 'Route not found']);
