<?php

namespace App\Core;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/pages/' . $view . '.php';
        $layoutFile = __DIR__ . '/../Views/layouts/main.php';

        if (file_exists($viewFile)) {
            // Bufferizar a view
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();

            // Incluir o layout base
            require_once $layoutFile;
        } else {
            die("View $view não encontrada.");
        }
    }
    
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
