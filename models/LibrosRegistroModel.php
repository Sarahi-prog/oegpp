<?php
require_once './config/DB.php';

class LibrosRegistroModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    // 🔹 LISTAR LIBROS
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

    // 🔹 GUARDAR LIBRO
    public function guardar($data) {
        try {
            $sql = "INSERT INTO libros_registro 
                    (tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                $data['tipo'],
                $data['numero_libro'],
                $data['anio_inicio'],
                $data['fecha_fin'] ?: null,
                $data['distrito'] ?? null,
                $data['provincia'] ?? null,
                $data['descripcion'] ?? null
            ]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al guardar libro: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 MODIFICAR LIBRO
    public function modificar($data) {
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
                $data['tipo'],
                $data['numero_libro'],
                $data['anio_inicio'],
                $data['fecha_fin'] ?: null,
                $data['distrito'] ?? null,
                $data['provincia'] ?? null,
                $data['descripcion'] ?? null,
                $data['id_libro']
            ]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al modificar libro: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 ELIMINAR (opcional pero recomendable)
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM libros_registro WHERE id_libro = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al eliminar libro: " . $e->getMessage());
            return false;
        }
    }
}
?>