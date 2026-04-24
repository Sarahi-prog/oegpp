<?php
class NotaPivot {
    private $idNota;
    private $cursoId;
    private $cursoNombre;
    private $moduloId;
    private $moduloNombre;
    private $trabajadorId;
    private $trabajadorNombre;
    private $nota;
    private $fechaRegistro;

    // Getters y setters
    public function setIdNota($id) { $this->idNota = $id; }
    public function getIdNota() { return $this->idNota; }

    public function setCursoId($id) { $this->cursoId = $id; }
    public function getCursoId() { return $this->cursoId; }

    public function setCursoNombre($nombre) { $this->cursoNombre = $nombre; }
    public function getCursoNombre() { return $this->cursoNombre; }

    public function setModuloId($id) { $this->moduloId = $id; }
    public function getModuloId() { return $this->moduloId; }

    public function setModuloNombre($nombre) { $this->moduloNombre = $nombre; }
    public function getModuloNombre() { return $this->moduloNombre; }

    public function setTrabajadorId($id) { $this->trabajadorId = $id; }
    public function getTrabajadorId() { return $this->trabajadorId; }

    public function setTrabajadorNombre($nombre) { $this->trabajadorNombre = $nombre; }
    public function getTrabajadorNombre() { return $this->trabajadorNombre; }

    public function setNota($nota) { $this->nota = $nota; }
    public function getNota() { return $this->nota; }

    public function setFechaRegistro($fecha) { $this->fechaRegistro = $fecha; }
    public function getFechaRegistro() { return $this->fechaRegistro; }
}
?>