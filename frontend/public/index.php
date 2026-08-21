<?php
define('SRC', dirname(__DIR__) . '/src');

// Frontend Entry Point
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Basic Router for Frontend
$routes = [
    '/' => '../src/features/home/pages/home.php',
    '/login' => '../src/features/auth/pages/login.php',
    '/pdv' => '../src/features/pdv/pages/pdv.php',
    '/comandas' => '../src/features/comandas/pages/comandas.php',
    '/cozinha' => '../src/features/cozinha/pages/cozinha.php',
    '/estoque' => '../src/features/estoque/pages/estoque.php',
    '/equipe' => '../src/features/equipe/pages/equipe.php',
    '/config' => '../src/features/config/pages/config.php',
    '/relatorios' => '../src/features/relatorios/pages/relatorios.php',
];

if (array_key_exists($uri, $routes)) {
    $file = __DIR__ . '/' . $routes[$uri];
        ob_start();
        require_once $file;
        $content = ob_get_clean();
        
        $layoutFile = __DIR__ . '/../src/layouts/main.php';
        if ($uri === '/login') {
            $layoutFile = __DIR__ . '/../src/layouts/auth.php';
        }
        
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
} else {
    // Serve static assets if they exist (for PHP built-in server)
    $assetPath = __DIR__ . $uri;
    if (is_file($assetPath)) {
        return false; // let the built-in server handle it
    }
    
    http_response_code(404);
    echo "Página não encontrada";
}
