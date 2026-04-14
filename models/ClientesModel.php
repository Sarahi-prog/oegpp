<?php
require_once './config/DB.php'; 

class ClientesModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar(); 
    }

    // 1. Obtener Clientes
    public function cargar() {
        try {
            $query = "SELECT * FROM clientes ORDER BY id_cliente DESC";
            $stmt = $this->conexion->query($query);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados ? $resultados : [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Obtener Cursos (Para el formulario)
    public function cargarCursos() {
        try {
            $query = "SELECT id_curso, codigo_curso, nombre_curso FROM cursos ORDER BY nombre_curso ASC";
            $stmt = $this->conexion->query($query);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados ? $resultados : [];
        } catch (PDOException $e) { return []; }
    }

    // 3. Obtener Libros (Para el formulario)
    public function cargarLibros() {
        try {
            $query = "SELECT id_libro, tipo, numero_libro, anio_inicio FROM libros_registro ORDER BY id_libro DESC";
            $stmt = $this->conexion->query($query);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados ? $resultados : [];
        } catch (PDOException $e) { return []; }
    }

    // 4. Guardar Nuevo Cliente
    public function guardarNuevoCliente($cliente) {
        try {
            $sql = "INSERT INTO clientes (dni, nombres, apellidos, correo, celular, area, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id_cliente";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $cliente->getDni(),
                $cliente->getNombres(),
                $cliente->getApellidos(),
                $cliente->getCorreo(),
                $cliente->getCelular(),
                $cliente->getArea(),
                $cliente->getEstado()
            ]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_cliente'] : null; 
            
        } catch (PDOException $e) {
            die("Error BD (Clientes): " . $e->getMessage());
        }
    }
}
   
?>