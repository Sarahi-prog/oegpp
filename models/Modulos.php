<?php
class Modulos {
    private $id_modulo;
    private $curso_id;
    private $nombre_modulo;
    private $horas;
    private $fecha_inicio;
    private $fecha_fin;

    public function getIdModulo() {
        return $this->id_modulo;
    }

    public function setIdModulo($id_modulo) {
        $this->id_modulo = $id_modulo;
    }

    public function getCursoId() {
        return $this->curso_id;
    }

    public function setCursoId($curso_id) {
        $this->curso_id = $curso_id;
    }

    public function getNombreModulo() {
        return $this->nombre_modulo;
    }

    public function setNombreModulo($nombre_modulo) {
        $this->nombre_modulo = $nombre_modulo;
    }

    public function getHoras() {
        return $this->horas;
    }

    public function setHoras($horas) {
        $this->horas = $horas;
    }

    public function getFechaInicio() {
        return $this->fecha_inicio;
    }

    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFechaFin() {
        return $this->fecha_fin;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }
}
?>