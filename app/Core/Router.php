<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method)
    {
        // Limpar query string
        $uri = parse_url($uri, PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            // Suporte básico a rotas com parâmetros como /usuarios/{id}
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remover a correspondência completa

                if (is_callable($route['action'])) {
                    call_user_func_array($route['action'], $matches);
                    return;
                }

                if (is_string($route['action'])) {
                    list($controller, $method) = explode('@', $route['action']);
                    $controllerClass = "App\\Controllers\\" . $controller;
                    
                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();
                        if (method_exists($controllerInstance, $method)) {
                            call_user_func_array([$controllerInstance, $method], $matches);
                            return;
                        }
                    }
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 - Not Found";
    }
}
