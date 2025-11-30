<?php
/**
 * Router - Sistema de rutas amigables
 * Alianza Inclusiva Tech
 */

class Router {
    private $routes = [];
    private $notFoundCallback;

    public function get($pattern, $callback) {
        $this->routes['GET'][$pattern] = $callback;
    }

    public function post($pattern, $callback) {
        $this->routes['POST'][$pattern] = $callback;
    }

    public function match($methods, $pattern, $callback) {
        foreach ((array)$methods as $method) {
            $this->routes[strtoupper($method)][$pattern] = $callback;
        }
    }

    public function notFound($callback) {
        $this->notFoundCallback = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $pattern => $callback) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    // Filtrar solo los parámetros nombrados
                    $params = array_filter($matches, function($key) {
                        return !is_numeric($key);
                    }, ARRAY_FILTER_USE_KEY);

                    return $this->executeCallback($callback, $params);
                }
            }
        }

        // 404 Not Found
        if ($this->notFoundCallback) {
            http_response_code(404);
            return call_user_func($this->notFoundCallback);
        }

        http_response_code(404);
        echo "Página no encontrada";
    }

    private function getUri() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remover la base del script si existe
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $uri = str_replace($scriptName, '', $uri);
        }

        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Asegurar que empiece con /
        $uri = '/' . ltrim($uri, '/');

        // Remover trailing slash excepto en root
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    private function executeCallback($callback, $params = []) {
        if (is_array($callback)) {
            $controller = $callback[0];
            $method = $callback[1];

            if (is_string($controller)) {
                $controller = new $controller();
            }

            return call_user_func_array([$controller, $method], $params);
        }

        return call_user_func_array($callback, $params);
    }
}
