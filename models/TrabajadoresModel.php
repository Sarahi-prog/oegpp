<?php
require_once './config/DB.php'; 

class TrabajadoresModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar(); 
    }

    // 1. Obtener Trabajadores
    public function cargar() {
        try {
            $query = "SELECT * FROM trabajadores ORDER BY id_trabajador DESC";
            $stmt = $this->conexion->query($query);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados ? $resultados : [];
        } catch (PDOException $e) { return []; }
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

    // 4. Guardar Nuevo Trabajador
    public function guardarNuevoTrabajador($dni, $nombres, $apellidos, $correo, $celular, $area) {
        try {
            $sql = "INSERT INTO trabajadores (dni, nombres, apellidos, correo, celular, area) 
                    VALUES (?, ?, ?, ?, ?, ?) RETURNING id_trabajador";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$dni, $nombres, $apellidos, $correo, $celular, $area]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['id_trabajador']; 
        } catch (PDOException $e) {
            die("Error BD (Trabajador): " . $e->getMessage());
        }
    }

    // 5. Asignar el Curso (INSERT en registros_capacitacion)
    public function asignarCurso($trabajador_id, $curso_id, $libro_id, $registro, $horas_realizadas, $fecha_inicio, $fecha_fin, $fecha_emision, $folio) {
        try {
            // Manejo de fechas nulas (En tu BD fecha_inicio y fecha_fin permiten nulos)
            $fecha_inicio = !empty($fecha_inicio) ? $fecha_inicio : null;
            $fecha_fin = !empty($fecha_fin) ? $fecha_fin : null;

            $sql = "INSERT INTO registros_capacitacion 
                    (trabajador_id, curso_id, libro_id, registro, horas_realizadas, fecha_inicio, fecha_fin, fecha_emision, folio) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                $trabajador_id, $curso_id, $libro_id, $registro, 
                $horas_realizadas, $fecha_inicio, $fecha_fin, $fecha_emision, $folio
            ]);
        } catch (PDOException $e) {
            die("Error BD (Asignación): " . $e->getMessage());
        }
    }
}
?>