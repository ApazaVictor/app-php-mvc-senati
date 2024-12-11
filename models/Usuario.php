<?php

class Usuario{
    private $conn;

    public $id_usuario;
    public $nombre_usuario;
    public $clave;
    public $correo;
    public $nombre_completo;
    public $rol;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($nombre_usuario, $clave_usuario){
        $query = "select * from usuario  where nombre_usuario = :nombre_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($clave_usuario, $row['clave'])){
                return $row;
            }

        }
        return false;
    }

    public function validaUsuario($usuario){
        $query = "select id_usuario from usuario where nombre_usuario = :nombre_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $usuario);
        $stmt->execute();

        return $stmt->rowCount()>0;
    }

    public function validaEmail($email){
        $query = "select id_usuario from usuario where correo = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount()>0;
        
    }

    public function registrarUsuario($usuarioData) {
        // Verifica que los datos necesarios estén presentes
        if (empty($usuarioData['usuario']) || empty($usuarioData['clave']) || empty($usuarioData['correo']) || empty($usuarioData['nombre_completo']) || empty($usuarioData['rol'])) {
            return false;  // Devuelve falso si algún campo requerido está vacío
        }
    
        // Consulta SQL
        $query = "
        INSERT INTO usuario 
        (nombre_usuario, clave, correo, nombre_completo, rol) 
        VALUES 
        (:nombre_usuario, :clave, :correo, :nombre_completo, :rol);
        ";
    
        // Asigna los valores a las propiedades
        $this->nombre_usuario = $usuarioData['usuario'];
        $this->clave = password_hash($usuarioData['clave'], PASSWORD_DEFAULT);
        $this->correo = $usuarioData['correo'];
        $this->nombre_completo = $usuarioData['nombre_completo'];
        $this->rol = $usuarioData['rol'];
    
        // Prepara la consulta y vincula los parámetros
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':clave', $this->clave);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':nombre_completo', $this->nombre_completo);
        $stmt->bindParam(':rol', $this->rol);
    
        // Ejecuta la consulta y devuelve el resultado
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}