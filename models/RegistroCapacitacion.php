<?php
class RegistroCapacitacion {
    private $id_registro;
    private $cliente_id;
    private $curso_id;
    private $libro_id;
    private $registro;
    private $horas_realizadas;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_emision;
    private $folio;
    private $fecha_registro;
    private $estado;

    // Constructor
    public function __construct($data = []) {
        $this->id_registro = $data['id_registro'] ?? null;
        $this->cliente_id = $data['cliente_id'] ?? null;
        $this->curso_id = $data['curso_id'] ?? null;
        $this->libro_id = $data['libro_id'] ?? null;
        $this->registro = $data['registro'] ?? null;
        $this->horas_realizadas = $data['horas_realizadas'] ?? null;
        $this->fecha_inicio = $data['fecha_inicio'] ?? null;
        $this->fecha_fin = $data['fecha_fin'] ?? null;
        $this->fecha_emision = $data['fecha_emision'] ?? null;
        $this->folio = $data['folio'] ?? null;
        $this->fecha_registro = $data['fecha_registro'] ?? null;
        $this->estado = $data['estado'] ?? null;
    }

    // Getters y Setters
    public function getIdRegistro() { return $this->id_registro; }
    public function setIdRegistro($id) { $this->id_registro = $id; }

    public function getClienteId() { return $this->cliente_id; }
    public function setClienteId($id) { $this->cliente_id = $id; }

    public function getCursoId() { return $this->curso_id; }
    public function setCursoId($id) { $this->curso_id = $id; }

    public function getLibroId() { return $this->libro_id; }
    public function setLibroId($id) { $this->libro_id = $id; }

    public function getRegistro() { return $this->registro; }
    public function setRegistro($r) { $this->registro = trim($r); }

    public function getHorasRealizadas() { return $this->horas_realizadas; }
    public function setHorasRealizadas($h) { $this->horas_realizadas = (int)$h; }

    public function getFechaInicio() { return $this->fecha_inicio; }
    public function setFechaInicio($f) { $this->fecha_inicio = $f; }

    public function getFechaFin() { return $this->fecha_fin; }
    public function setFechaFin($f) { $this->fecha_fin = $f; }

    public function getFechaEmision() { return $this->fecha_emision; }
    public function setFechaEmision($f) { $this->fecha_emision = $f; }

    public function getFolio() { return $this->folio; }
    public function setFolio($f) { $this->folio = trim($f); }

    public function getFechaRegistro() { return $this->fecha_registro; }
    public function setFechaRegistro($f) { $this->fecha_registro = $f; }

    public function getEstado() { return $this->estado; }
    public function setEstado($e) { $this->estado = trim($e); }
}
?>