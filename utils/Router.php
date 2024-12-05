<?php
class Router {
    private $routes = [];
    
    public function add($method, $path, $controller, $action) {
        // Normalizar la ruta
        $path = trim($path, '/');
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path) . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch($request_url, $request_method) {
        // Obtener la URL limpia
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = trim($url, '/');
        
        if (empty($url)) {
            $url = '/';
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request_method) {
                continue;
            }
            
            if ($url === '/' && $route['pattern'] === '#^$#') {

                $controller = new $route['controller']();
                $action = $route['action'];
                $controller->$action();
                return;
            }
            
            if (preg_match($route['pattern'], $url, $matches)) {
                // Remover índices numéricos
                foreach ($matches as $key => $match) {
                    if (is_int($key)) {
                        unset($matches[$key]);
                    }
                }
                
                $controller = new $route['controller']();
                $action = $route['action'];
                $controller->$action($matches);
                return;
            }
        }
        
        // Si no se encuentra la ruta, mostrar error 404
        header("HTTP/1.0 404 Not Found");
        include 'views/errors/404.php';
    }
}