<?php
require_once './config/DB.php';

class CursosModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    // ✔ LISTAR CURSOS (igual que clientes)
    public function cargar() {
        try {
            $sql = "SELECT id_curso, codigo_curso, nombre_curso, tipo, horas_totales, estado 
                    FROM cursos 
                    ORDER BY id_curso DESC";

            $stmt = $this->conexion->query($sql);

            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        } catch (PDOException $e) {
            return [];
        }
    }

    // ✔ SOLO DIPLOMADOS
    public function cargarDiplomados() {
        try {
            $sql = "SELECT * FROM cursos WHERE tipo = 'diplomados' ORDER BY id_curso DESC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // ✔ SOLO CERTIFICADOS
    public function cargarCertificados() {
        try {
            $sql = "SELECT * FROM cursos WHERE tipo = 'certificados' ORDER BY id_curso DESC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // ✔ GUARDAR (SIN CLASES)
    public function guardarCurso() {
        try {
            $sql = "INSERT INTO cursos (codigo_curso, nombre_curso, tipo, horas_totales, estado)
                    VALUES (?, ?, ?, ?, ?) RETURNING id_curso";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $_POST['codigo_curso'],
                $_POST['nombre_curso'],
                $_POST['tipo'],
                $_POST['horas_totales'],
                $_POST['estado']
            ]);

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_curso'] : null;

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error BD (Cursos): " . $e->getMessage());
            return null;
        }
    }

    // ✔ MODIFICAR
    public function modificarCurso() {
        try {
            $sql = "UPDATE cursos SET 
                        codigo_curso = ?, 
                        nombre_curso = ?, 
                        tipo = ?, 
                        horas_totales = ?, 
                        estado = ?
                    WHERE id_curso = ?";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                $_POST['codigo_curso'],
                $_POST['nombre_curso'],
                $_POST['tipo'],
                $_POST['horas_totales'],
                $_POST['estado'],
                $_POST['id_curso']
            ]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return false;
        }
    }

    // ✔ ELIMINAR
    public function eliminarCurso($id_curso) {
        try {
            $sql = "DELETE FROM cursos WHERE id_curso = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_curso]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return false;
        }
    }

    // ✔ CAMBIAR ESTADO
    public function actualizarEstado($id_curso, $estado) {
        try {
            $sql = "UPDATE cursos SET estado = ? WHERE id_curso = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$estado, $id_curso]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>