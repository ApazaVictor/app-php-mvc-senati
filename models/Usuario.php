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
}