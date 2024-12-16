<?php
//Manejo de errores
error_reporting(E_ALL);
ini_set('display_errors',1);

//Cargar el archivo de configuración
require_once 'config/config.php';

//Autoload de clases
spl_autoload_register(function ($class_name) {
    $directories = [
        'controllers/',
        'models/',
        'config/',
        'utils/',
        ''
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            // var_dump($file);
            require_once $file;
            return;
        }
    }
});

//Crear una instancia del router
$router = new Router();

$public_routes = [
    '/web',
    '/login',
    '/register',
];

//Obtener la ruta actual
$current_route = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$current_route = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $current_route);
//$current_route = la ruta despues de la carpeta del proyecto.


$router->add('GET','/web','WebController','index');
//login and Register
$router->add('GET','/login','AuthController','showLogin');
$router->add('GET','/register','AuthController','showRegister');

$router->add('POST','auth/login','AuthController','login');
$router->add('POST','auth/register','AuthController','register');

//HomeController
$router->add('GET','/home','HomeController','index');

//CRUD PRODUCTOS//
$router->add('GET','productos/','ProductoController','index');
$router->add('GET','productos/obtener-todo','ProductoController','obtenerProducto');
$router->add('POST','productos/guardar-producto','ProductoController','guardarProducto');
$router->add('POST','productos/actualizar-proucto','ProductoController','actualizarProducto');
$router->add('DELETE','productos/eliminar-producto','ProductoController','eliminarProducto');
$router->add('GET','productos/buscar-producto','ProductoController','buscarProducto');

//Despachar la ruta
try {
    $router->dispatch($current_route, $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    // Manejar el error
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        include 'views/errors/404.php';
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}