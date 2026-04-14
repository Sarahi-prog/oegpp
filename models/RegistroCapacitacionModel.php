<?php
require_once __DIR__ . '/../config/DB.php';
require_once __DIR__ . '/RegistroCapacitacion.php';

class RegistroCapacitacionModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function cargar() {
        $sql = "SELECT rc.*, 
                       t.nombres || ' ' || t.apellidos AS nombre_trabajador,
                       c.nombre_curso,
                       l.tipo || ' ' || l.numero_libro AS nombre_libro
                FROM registros_capacitacion rc
                LEFT JOIN trabajadores t ON rc.trabajador_id = t.id_trabajador
                LEFT JOIN cursos c ON rc.curso_id = c.id_curso
                LEFT JOIN libros_registro l ON rc.libro_id = l.id_libro
                ORDER BY rc.id_registro DESC";

        $ps = $this->db->prepare($sql);
        $ps->execute();
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : [];
    }

    public function guardar(RegistroCapacitacion $registro) {
        $sql = "INSERT INTO registros_capacitacion (
                    trabajador_id,
                    curso_id,
                    libro_id,
                    registro,
                    horas_realizadas,
                    fecha_inicio,
                    fecha_fin,
                    fecha_emision,
                    folio
                ) VALUES (
                    :trabajador_id,
                    :curso_id,
                    :libro_id,
                    :registro,
                    :horas_realizadas,
                    :fecha_inicio,
                    :fecha_fin,
                    :fecha_emision,
                    :folio
                )";

        $ps = $this->db->prepare($sql);

        $trabajador_id = $registro->getTrabajadorId();
        $curso_id = $registro->getCursoId();
        $libro_id = $registro->getLibroId();
        $registro_num = $registro->getRegistro();
        $horas_realizadas = $registro->getHorasRealizadas();
        
        // CORRECCIÓN: Convertir textos vacíos a valores nulos reales para PostgreSQL
        $fecha_inicio = $registro->getFechaInicio() === '' ? null : $registro->getFechaInicio();
        $fecha_fin = $registro->getFechaFin() === '' ? null : $registro->getFechaFin();
        $fecha_emision = $registro->getFechaEmision() === '' ? null : $registro->getFechaEmision();
        
        $folio = $registro->getFolio();

        $ps->bindParam(':trabajador_id', $trabajador_id);
        $ps->bindParam(':curso_id', $curso_id);
        $ps->bindParam(':libro_id', $libro_id);
        $ps->bindParam(':registro', $registro_num);
        $ps->bindParam(':horas_realizadas', $horas_realizadas);
        $ps->bindParam(':fecha_inicio', $fecha_inicio);
        $ps->bindParam(':fecha_fin', $fecha_fin);
        $ps->bindParam(':fecha_emision', $fecha_emision);
        $ps->bindParam(':folio', $folio);
        $ps->execute();
    }

    public function modificar(RegistroCapacitacion $registro) {
        $sql = "UPDATE registros_capacitacion SET 
                    trabajador_id = :trabajador_id,
                    curso_id = :curso_id,
                    libro_id = :libro_id,
                    registro = :registro,
                    horas_realizadas = :horas_realizadas,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    fecha_emision = :fecha_emision,
                    folio = :folio
                WHERE id_registro = :id_registro";

        $ps = $this->db->prepare($sql);

        $id_registro = $registro->getIdRegistro();
        $trabajador_id = $registro->getTrabajadorId();
        $curso_id = $registro->getCursoId();
        $libro_id = $registro->getLibroId();
        $registro_num = $registro->getRegistro();
        $horas_realizadas = $registro->getHorasRealizadas();
        
        // CORRECCIÓN: Convertir textos vacíos a valores nulos reales para PostgreSQL
        $fecha_inicio = $registro->getFechaInicio() === '' ? null : $registro->getFechaInicio();
        $fecha_fin = $registro->getFechaFin() === '' ? null : $registro->getFechaFin();
        $fecha_emision = $registro->getFechaEmision() === '' ? null : $registro->getFechaEmision();
        
        $folio = $registro->getFolio();

        $ps->bindParam(':id_registro', $id_registro);
        $ps->bindParam(':trabajador_id', $trabajador_id);
        $ps->bindParam(':curso_id', $curso_id);
        $ps->bindParam(':libro_id', $libro_id);
        $ps->bindParam(':registro', $registro_num);
        $ps->bindParam(':horas_realizadas', $horas_realizadas);
        $ps->bindParam(':fecha_inicio', $fecha_inicio);
        $ps->bindParam(':fecha_fin', $fecha_fin);
        $ps->bindParam(':fecha_emision', $fecha_emision);
        $ps->bindParam(':folio', $folio);
        $ps->execute();
    }
}
?>