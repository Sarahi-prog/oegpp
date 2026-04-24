<?php
require_once './config/DB.php';
require_once 'Cursos.php';

class CursosModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    private function mapearCursos($filas) {
        $cursos = array();
        foreach ($filas as $f) {
            $cur = new Cursos();
            $cur->setIdCurso($f[0]);
            $cur->setCodigoCurso($f[1]);
            $cur->setNombreCurso($f[2]);
            $cur->setTipo($f[3]);
            $cur->setHorasTotales($f[4]);
            $cursos[] = $cur;
        }
        return $cursos;
    }

    public function cargarCurso() {
        $sql = "SELECT * FROM cursos ORDER BY id_curso DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearCursos($ps->fetchAll());
    }

    public function cargarD() {
        $sql = "SELECT * FROM cursos WHERE tipo = 'diplomados' ORDER BY id_curso DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearCursos($ps->fetchAll());
    }

    public function cargarC() {
        $sql = "SELECT * FROM cursos WHERE tipo = 'certificados' ORDER BY id_curso DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearCursos($ps->fetchAll());
    }

    public function guardarCurso(Cursos $curso) {
        try {
            $sql = "INSERT INTO cursos (codigo_curso, nombre_curso, tipo, horas_totales) 
                    VALUES (:cc, :nc, :t, :ht)";
            $ps = $this->db->prepare($sql);
            return $ps->execute([
                ":cc" => $curso->getCodigoCurso(),
                ":nc" => $curso->getNombreCurso(),
                ":t"  => $curso->getTipo(),
                ":ht" => $curso->getHorasTotales()
            ]);
        } catch (PDOException $e) {
            error_log("Error al guardar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MODIFICADO: Se corrigió el bloque try-catch y se añadió retorno
     */
    public function modificarCurso(Cursos $curso) {
        try { // <--- Faltaba este try
            $sql = "UPDATE cursos SET 
                        codigo_curso=:cc, 
                        nombre_curso=:nc, 
                        tipo=:t, 
                        horas_totales=:ht
                    WHERE id_curso=:id";
            $ps = $this->db->prepare($sql);
            
            return $ps->execute([ // <--- Retornamos el resultado (true/false)
                ":id" => $curso->getIdCurso(),
                ":cc" => $curso->getCodigoCurso(),
                ":nc" => $curso->getNombreCurso(),
                ":t"  => $curso->getTipo(),
                ":ht" => $curso->getHorasTotales()
            ]);
        } catch (PDOException $e) {
            error_log("Error al modificar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MODIFICADO: Añadido try-catch por seguridad (llaves foráneas)
     */
    public function eliminarCurso($id_curso) {
        try {
            $sql = "DELETE FROM cursos WHERE id_curso=:id";
            $ps = $this->db->prepare($sql);
            return $ps->execute([":id" => $id_curso]);
        } catch (PDOException $e) {
            // Esto fallará si el curso ya está asignado a un alumno (integridad referencial)
            error_log("Error al eliminar: " . $e->getMessage());
            return false;
        }
    }
} 

?>