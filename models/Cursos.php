<?php
class Cursos {
    private $id_curso;
    private $codigo_curso;
    private $nombre_curso;
    private $tipo;
    private $horas_totales;

    public function __construct() {}

    // Getters y Setters
    public function getIdCurso() { return $this->id_curso; }
    public function setIdCurso($id) { $this->id_curso = $id; }

    public function getCodigoCurso() { return $this->codigo_curso; }
    public function setCodigoCurso($codigo) { $this->codigo_curso = $codigo; }

    public function getNombreCurso() { return $this->nombre_curso; }
    public function setNombreCurso($nombre) { $this->nombre_curso = $nombre; }

    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }

    public function getHorasTotales() { return $this->horas_totales; }
    public function setHorasTotales($horas) { $this->horas_totales = $horas; }
}
?>