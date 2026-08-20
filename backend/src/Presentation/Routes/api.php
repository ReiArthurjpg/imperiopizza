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

if ($uri === '/api/dashboard/movimentacoes-recentes' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getMovimentacoesRecentes();
    exit;
}

if ($uri === '/api/dashboard/equipe-operacao' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getEquipeOperacao();
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

if ($uri === '/api/operacao/iniciar' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->startOperacao();
    exit;
}

if ($uri === '/api/operacao/equipe' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getEquipeOperacao();
    exit;
}

if ($uri === '/api/profissionais' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->createProfissional();
    exit;
}

if (preg_match('/^\/api\/profissionais\/([a-zA-Z0-9_-]+)$/', $uri, $matches) && $method === 'PUT') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->updateProfissional($matches[1]);
    exit;
}

if (preg_match('/^\/api\/profissionais\/([a-zA-Z0-9_-]+)$/', $uri, $matches) && $method === 'DELETE') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->deleteProfissional($matches[1]);
    exit;
}

if ($uri === '/api/profissionais' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getProfissionais();
    exit;
}

if ($uri === '/api/mass-batch' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->createBatidaMassa();
    exit;
}

if ($uri === '/api/mass-stock' && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->updateMassStock();
    exit;
}

if ($uri === '/api/mass/kpis' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getMassKpis();
    exit;
}

if ($uri === '/api/mass/stock' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getMassStock();
    exit;
}

if ($uri === '/api/mass/history' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getMassHistory();
    exit;
}

if ($uri === '/api/mass/recipe' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getMassRecipe();
    exit;
}

// ---------------------------------------------------------
// ROTAS DE ATENDIMENTO / DESPACHO
// ---------------------------------------------------------

if ($uri === '/api/operations/current' && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getCurrentOperation();
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/dispatch\/queue$/', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getDispatchQueue($matches[1]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/dispatch\/stats$/', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getDispatchStats($matches[1]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/oven$/', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->getOvenQueue($matches[1]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/dispatch\/pull-from-oven$/', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->pullFromOven($matches[1]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/commands\/([a-zA-Z0-9_-]+)\/dispatch\/check$/', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->checkDispatch($matches[1], $matches[2]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/commands\/([a-zA-Z0-9_-]+)\/dispatch\/send-delivery$/', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->sendDelivery($matches[1], $matches[2]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/commands\/([a-zA-Z0-9_-]+)\/dispatch\/revert$/', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->revertDispatch($matches[1], $matches[2]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/commands\/([a-zA-Z0-9_-]+)\/dispatch$/', $uri, $matches) && $method === 'PUT') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->updateDispatch($matches[1], $matches[2]);
    exit;
}

if (preg_match('/^\/api\/operations\/([a-zA-Z0-9_-]+)\/finish-day$/', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/ApiController.php';
    $controller = new \App\Back\Presentation\Controllers\ApiController();
    $controller->finishDay($matches[1]);
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
