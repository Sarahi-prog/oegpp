<?php
require_once './config/DB.php';
require_once 'Modulos.php';

class ModulosModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function buscarPorCurso($id_curso) {
        $sql = "SELECT id_modulo, curso_id, nombre_modulo, horas, fecha_inicio, fecha_fin, estado FROM modulos WHERE curso_id = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(':id', $id_curso, PDO::PARAM_INT);
        $ps->execute();
        return $this->mapearModulos($ps->fetchAll(PDO::FETCH_ASSOC));
    }

    public function cargar() {
        $sql = "SELECT id_modulo, curso_id, nombre_modulo, horas, fecha_inicio, fecha_fin, estado FROM modulos";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $this->mapearModulos($ps->fetchAll(PDO::FETCH_ASSOC));
    }

    private function mapearModulos($filas) {
        $modulos = [];
        foreach($filas as $f) {
            $mod = new Modulos();
            $mod->setIdModulo($f['id_modulo']);
            $mod->setCursoId($f['curso_id']);
            $mod->setNombreModulo($f['nombre_modulo']);
            $mod->setHoras($f['horas']);
            $mod->setFechaInicio($f['fecha_inicio']);
            $mod->setFechaFin($f['fecha_fin']);
            $mod->setEstado($f['estado']);
            $modulos[] = $mod;
        }
        return $modulos;
    }

    public function guardar(Modulos $modulo) {
        $sql = "INSERT INTO modulos (curso_id, nombre_modulo, horas, fecha_inicio, fecha_fin, estado) 
                VALUES (:cid, :nm, :h, :fi, :ff, 1)";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":cid", $modulo->getCursoId());
        $ps->bindValue(":nm", $modulo->getNombreModulo());
        $ps->bindValue(":h", $modulo->getHoras());
        $ps->bindValue(":fi", $modulo->getFechaInicio());
        $ps->bindValue(":ff", $modulo->getFechaFin());
        $ps->execute();
    }

    public function modificar(Modulos $modulo) {
        $sql = "UPDATE modulos SET curso_id = :cid, nombre_modulo = :nm, horas = :h, 
                fecha_inicio = :fi, fecha_fin = :ff WHERE id_modulo = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":cid", $modulo->getCursoId());
        $ps->bindValue(":nm", $modulo->getNombreModulo());
        $ps->bindValue(":h", $modulo->getHoras());
        $ps->bindValue(":fi", $modulo->getFechaInicio());
        $ps->bindValue(":ff", $modulo->getFechaFin());
        $ps->bindValue(":id", $modulo->getIdModulo(), PDO::PARAM_INT);
        return $ps->execute();
    }

    public function cambiarEstado($id_modulo, $estado) {
        $sql = "UPDATE modulos SET estado = :estado WHERE id_modulo = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":estado", $estado, PDO::PARAM_INT);
        $ps->bindValue(":id", $id_modulo, PDO::PARAM_INT);
        return $ps->execute();
    }

    public function eliminar($id_modulo) {
        $sql = "DELETE FROM modulos WHERE id_modulo = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":id", $id_modulo, PDO::PARAM_INT);
        return $ps->execute();
    }
}