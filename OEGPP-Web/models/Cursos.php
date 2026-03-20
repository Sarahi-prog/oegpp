<?php
class Cursos {
    private $id_curso;
    private $codigo_curso;
    private $nombre_curso;
    private $tipo;
    private $horas_totales;

    pubclic function getIdCurso() {
        return $this->id_curso;
    }
    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }
    public function getCodigoCurso() {
        return $this->codigo_curso;
    }
    public function setCodigoCurso($codigo_curso) {
        $this->codigo_curso = $codigo_curso;
    }
    public function getNombreCurso() {
        return $this->nombre_curso;
    }
    public function setNombreCurso($nombre_curso) {
        $this->nombre_curso = $nombre_curso;
    }
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    public function getHorasTotales() {
        return $this->horas_totales;
    }
    public function setHorasTotales($horas_totales) {
        $this->horas_totales = $horas_totales;
}
?>