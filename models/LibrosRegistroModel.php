<?php
require_once './config/DB.php';

class LibrosRegistroModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    // 🔹 LISTAR
    public function cargar() {
        try {
            $sql = "SELECT id_libro, tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion 
                    FROM libros_registro 
                    ORDER BY id_libro DESC";

            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return [];
        }
    }

    // 🔹 GUARDAR (igual que clientes)
    public function guardarLibro($libro) {
        try {
            $sql = "INSERT INTO libros_registro 
                    (tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?) 
                    RETURNING id_libro";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $libro->getTipo(),
                $libro->getNumeroLibro(),
                $libro->getAnioInicio(),
                $libro->getFechaFin() ?: null,
                $libro->getDistrito(),
                $libro->getProvincia(),
                $libro->getDescripcion()
            ]);

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_libro'] : null;

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al guardar libro: " . $e->getMessage());
            return null;
        }
    }

    // 🔹 MODIFICAR (igual que clientes)
    public function modificarLibro($libro) {
        try {
            $sql = "UPDATE libros_registro SET 
                        tipo = ?, 
                        numero_libro = ?, 
                        anio_inicio = ?, 
                        fecha_fin = ?, 
                        distrito = ?, 
                        provincia = ?, 
                        descripcion = ?
                    WHERE id_libro = ?";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                $libro->getTipo(),
                $libro->getNumeroLibro(),
                $libro->getAnioInicio(),
                $libro->getFechaFin() ?: null,
                $libro->getDistrito(),
                $libro->getProvincia(),
                $libro->getDescripcion(),
                $libro->getIdLibro()
            ]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al modificar libro: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 ELIMINAR
    public function eliminarLibro($id_libro) {
        try {
            $sql = "DELETE FROM libros_registro WHERE id_libro = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_libro]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al eliminar libro: " . $e->getMessage());
            return false;
        }
    }
}
?>