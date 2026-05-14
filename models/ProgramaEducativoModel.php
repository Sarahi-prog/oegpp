<?php
require_once './config/DB.php';
require_once 'ProgramaEducativo.php';

class ProgramaEducativoModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    private function mapearProgramaEducativo($filas) {
        $peducativo = array();
        foreach ($filas as $f) {
            $cur = new ProgramaEducativo();
            $cur->setIdPrograma($f[0]);
            $cur->setCodigo($f[1]);
            $cur->setNombre($f[2]);
            $cur->setTipo($f[3]);
            $cur->setHorasTotales($f[4]);
            $cur->setEstado($f[5]);
            $peducativo[] = $cur;
        }
        return $peducativo;
    }

    public function cargarProgramaEducativo() {
        $sql = "SELECT * FROM programa_educativo ORDER BY id_programa DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearProgramaEducativo($ps->fetchAll());
    }

    public function cargarD() {
        $sql = "SELECT * FROM programa_educativo WHERE tipo = 'diplomados' ORDER BY id_programa DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearProgramaEducativo($ps->fetchAll());
    }

    public function cargarC() {
        $sql = "SELECT * FROM programa_educativo WHERE tipo = 'certificados' ORDER BY id_programa DESC;";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearProgramaEducativo($ps->fetchAll());
    }

    public function cargar() {
    return $this->cargarProgramaEducativo();
}

    public function guardarProgramaEducativo(ProgramaEducativo $programaEducativo) {
        try {
            $sql = "INSERT INTO programa_educativo (codigo, nombre, tipo, horas_totales, estado) 
                    VALUES (:cc, :nc, :t, :ht, :e)";
            $ps = $this->db->prepare($sql);
            return $ps->execute([
                ":cc" => $programaEducativo->getCodigo(),
                ":nc" => $programaEducativo->getNombre(),
                ":t"  => $programaEducativo->getTipo(),
                ":ht" => $programaEducativo->getHorasTotales(),
                ":e"  => $programaEducativo->getEstado()
            ]);
        } catch (PDOException $e) {
            error_log("Error al guardar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MODIFICADO: Se corrigió el bloque try-catch y se añadió retorno
     */
    public function modificarProgramaEducativo(ProgramaEducativo $programaEducativo) {
        try { // <--- Faltaba este try
            $sql = "UPDATE programa_educativo SET 
                        codigo=:cc, 
                        nombre=:nc, 
                        tipo=:t, 
                        horas_totales=:ht,
                        estado=:e
                    WHERE id=:id";
            $ps = $this->db->prepare($sql);       
            return $ps->execute([ // <--- Retornamos el resultado (true/false)
                ":id" => $programaEducativo->getIdPrograma(),
                ":cc" => $programaEducativo->getCodigo(),
                ":nc" => $programaEducativo->getNombre(),
                ":t"  => $programaEducativo->getTipo(),
                ":ht" => $programaEducativo->getHorasTotales(),
                ":e"  => $programaEducativo->getEstado()
            ]);
        } catch (PDOException $e) {
            error_log("Error al modificar: " . $e->getMessage());
            return false;
        }
    }
    /**
     * MODIFICADO: Añadido try-catch por seguridad (llaves foráneas)
     */
    public function eliminarProgramaEducativo($id) {
        try {
            $sql = "DELETE FROM programa_educativo WHERE id_programa=:id";
            $ps = $this->db->prepare($sql);
            return $ps->execute([":id" => $id]);
        } catch (PDOException $e) {
            // Esto fallará si el curso ya está asignado a un alumno (integridad referencial)
            error_log("Error al eliminar: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE programa_educativo SET estado = :e WHERE id = :id";
            $ps = $this->db->prepare($sql);
            return $ps->execute([
                ":e"  => $estado,
                ":id" => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }
} 
?>