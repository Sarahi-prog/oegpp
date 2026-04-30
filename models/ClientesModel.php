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

            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: []; 
            } catch (PDOException $e) {
                return [];
            }
    }

    public function guardarCliente($cliente) {
        try {
            $sql = "INSERT INTO clientes (dni, nombres, apellidos, correo, celular, area, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id_cliente";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $cliente->getDni($_POST['dni']),
                $cliente->getNombres($_POST['nombres']),
                $cliente->getApellidos($_POST['apellidos']),
                $cliente->getCorreo($_POST['correo']),
                $cliente->getCelular($_POST['celular']),
                $cliente->getArea($_POST['area']),
                $cliente->getEstado($_POST['estado']) // 'Activo' o 'Inactivo'
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

    public function modificarCliente($cliente) {
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
                $cliente->getDni($_POST['dni']),
                $cliente->getNombres($_POST['nombres']),
                $cliente->getApellidos($_POST['apellidos']),
                $cliente->getCorreo($_POST['correo']),
                $cliente->getCelular($_POST['celular']),
                $cliente->getArea($_POST['area']),
                $cliente->getEstado($_POST['estado']),
                $cliente->getIdCliente($_POST['id_cliente']) // El ID es necesario para el WHERE
            ]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al modificar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCliente($id_cliente) {
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

    public function buscarPorDni($dni) {
    try {
        $sql = "SELECT * FROM clientes WHERE dni = :dni LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}



}
?>