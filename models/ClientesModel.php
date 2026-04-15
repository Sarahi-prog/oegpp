<?php
require_once './config/DB.php'; 

class ClientesModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar(); 
    }

    // 1. Obtener Clientes (Lista completa para la tabla)
    public function cargar() {
        try {
            // Seleccionamos solo las columnas reales de tu tabla 'clientes'
            $query = "SELECT id_cliente, dni, nombres, apellidos, correo, estado FROM clientes ORDER BY id_cliente DESC";
            $stmt = $this->conexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Guardar Nuevo Cliente
    // Ajustado a tu tabla: id_cliente, dni, nombres, apellidos, correo, estado
    public function guardarNuevoCliente($cliente) {
        try {
            // Eliminamos 'celular' y 'area' porque no existen en tu tabla SQL
            $sql = "INSERT INTO clientes (dni, nombres, apellidos, correo, estado) 
                    VALUES (?, ?, ?, ?, ?) RETURNING id_cliente";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $cliente->getDni(),
                $cliente->getNombres(),
                $cliente->getApellidos(),
                $cliente->getCorreo(),
                $cliente->getEstado() // 'Activo' o 'Inactivo'
            ]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_cliente'] : null; 
            
        } catch (PDOException $e) {
            // Es mejor registrar el error que matar el proceso con die()
            error_log("Error BD (Clientes): " . $e->getMessage());
            return null;
        }
    }

    /* Nota: 'cargarCursos' y 'cargarLibros' no deberían estar aquí, 
       deberían estar en sus propios modelos (CursosModel y LibrosModel). 
       Sin embargo, si los necesitas para un "formulario rápido", están bien definidos.
    */
}
?>