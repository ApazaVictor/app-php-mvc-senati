<?php
class ProductoController
{

    private $db;
    private $producto;


    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        $datebase = new Database();
        $this->db = $datebase->connect();
        $this->producto = new Producto($this->db);
    }


    public function index()
    {
        include 'views/layouts/header.php'; //Siempre
        include 'views/producto/index.php';
        include 'views/layouts/footer.php'; //Siempre
    }

    public function obtenerProducto()
    {
        header('Content-Type: application/json');
        try {
            $resultado = $this->producto->obtenerProducto();
            $productos = $resultado->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'status' => 'success',
                'data' => $productos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}