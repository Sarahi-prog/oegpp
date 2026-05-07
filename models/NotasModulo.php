<?php
class NotasModulo {
    private $id_nota;
    private $cliente;
    private $modulo_id;
    private $nota;
    private $fecha_registro;

    public function getIdNota() { return $this->id_nota; }
    public function setIdNota($id_nota) { $this->id_nota = $id_nota; }

    public function getCliente() { return $this->cliente; }
    public function setCliente($cliente) { $this->cliente = $cliente; }

    public function getModuloId() { return $this->modulo_id; }
    public function setModuloId($modulo_id) { $this->modulo_id = $modulo_id; }

    public function getNota() { return $this->nota; }
    public function setNota($nota) { $this->nota = $nota; }

    public function getFechaRegistro() { return $this->fecha_registro; }
    public function setFechaRegistro($fecha_registro) { $this->fecha_registro = $fecha_registro; }
}
?>