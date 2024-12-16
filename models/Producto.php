<?php
//models/Producto.php
class Producto {
    private $conn;
    public $id_producto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerProducto() {
        $query = "SELECT * FROM producto ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } //

    public function crearProducto() {
        $query = "INSERT INTO producto  
                (nombre, descripcion, precio, stock, imagen) 
                VALUES (:nombre, :descripcion, :precio, :stock, :imagen)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':imagen', $this->imagen);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // public function read_single() {
    //     $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':id', $this->id);
    //     $stmt->execute();
        
    //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //     if($row) {
    //         $this->name = $row['name'];
    //         $this->description = $row['description'];
    //         $this->price = $row['price'];
    //         $this->stock = $row['stock'];
    //         $this->image = $row['image'];
    //         return true;
    //     }
    //     return false;
    // }

    public function actualizarProducto() {
        $query = "UPDATE producto
                SET nombre = :nombre, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    stock = :stock" .
                    ($this->imagen ? ", imagen = :imagen" : "") .
                " WHERE id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':id_producto', $this->id_producto);
        
        if($this->imagen) {
            $stmt->bindParam(':imagen', $this->imagen);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminarProducto() {
        $query = "DELETE FROM producto WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $this->id_producto);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // public function search($term) {
    //     $query = "SELECT * FROM " . $this->table . " 
    //              WHERE name LIKE :term 
    //              ORDER BY name ASC";
        
    //     $term = "%{$term}%";
        
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':term', $term);
        
    //     $stmt->execute();
    //     return $stmt;
    // }
}