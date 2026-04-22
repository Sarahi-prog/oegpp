<?php
require_once './config/DB.php'; 

class ClientesModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    public function cargar() {
        try {
            $query = "SELECT id_cliente, dni, nombres, apellidos, correo, celular, area, estado FROM clientes ORDER BY id_cliente DESC";
            $stmt = $this->conexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

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
                $cliente->getEstado() // 'Activo' o 'Inactivo'
            ]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_cliente'] : null; 
            
        } catch (PDOException $e) {
            // Registrar el error y guardar el mensaje para depuración
            $this->ultimoError = $e->getMessage();
            error_log("Error BD (Clientes): " . $e->getMessage());
            return null;
        }
    }

    public function modificar($cliente) {
        try {
            $sql = "UPDATE clientes SET 
                        dni = ?, 
                        nombres = ?, 
                        apellidos = ?, 
                        correo = ?, 
                        celular = ?, 
                        area = ?, 
                        estado = ? 
                    WHERE id_cliente = ?";
            
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                $cliente->getDni(),
                $cliente->getNombres(),
                $cliente->getApellidos(),
                $cliente->getCorreo(),
                $cliente->getCelular(),
                $cliente->getArea(),
                $cliente->getEstado(),
                $cliente->getIdCliente() // El ID es necesario para el WHERE
            ]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al modificar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_cliente) {
        try {
            $sql = "DELETE FROM clientes WHERE id_cliente = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_cliente]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al eliminar cliente: " . $e->getMessage());
            
            // Nota: Si el cliente tiene capacitaciones registradas, 
            // PostgreSQL lanzará un error de restricción de llave foránea.
            return false;
        }
    }



}
?>