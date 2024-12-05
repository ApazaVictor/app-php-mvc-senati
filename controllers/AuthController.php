<?php

class AuthController{
    //Atributos
    //Constructor
    //Método
    private $db;
    private $usuario;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        $database = new Database();
        $this->db = $database->connect();
        $this->usuario = new Usuario($this->db);
    }

    public function showLogin(){
        include 'views/auth/login.php';
    }
    
    public function showRegister(){
        include 'views/auth/register.php';
    }

    public function login (){
        header('Content-Type: application/json');
        try {
            
            $data = json_decode(file_get_contents("php://input"));

            if(empty($data->nombreUsuario) && empty($data->claveUsuario)){
                throw new Exception('Usuario y Contraseña son requeridos');
            }

            $usuario = $this->usuario->login($data->nombreUsuario, $data->claveUsuario);

            var_dump($usuario);

            //var_dump($data->nombreUsuario);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message'=>$e->getMessage()
            ]);
            
        }
    }
}