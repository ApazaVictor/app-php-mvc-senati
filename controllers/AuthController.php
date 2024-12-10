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

            if($usuario){
                session_start();
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

                echo json_encode([
                   'status' => 'success',
                   'message' => 'Login Exitoso',
                   'usuario' => [
                    'nombre_usuario' => $usuario['id_usuario'],
                    'rol' => $usuario['rol'],
                    'nombre_completo' => $usuario['nombre_completo'],
                   ]
                ]);

            }else{
                throw new Exception('Usuario y Contraseña incorrectos');
            }

            //var_dump($usuario);           
            //var_dump($data->nombreUsuario);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message'=>$e->getMessage()
            ]);
            
        }
    }

    public function register(){
        header('Content-Type: application/json');
    
        try {
            $data = json_decode(file_get_contents("php://input"));
    
    
            // Comparar y validar los dos campos de la contraseña
            if($data->clave !== $data->confirmarClave){
                throw new Exception('Las contraseñas no coinciden');
            }
            
    
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}