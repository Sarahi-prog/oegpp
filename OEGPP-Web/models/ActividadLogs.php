<?php
class ActividadLogs {
    private $id;
    private $usuario_id;
    private $accion;
    private $tabla_afectada;
    private $registro_id;
    private $descripcion;
    private $fecha;
    
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getUsuarioId() {
        return $this->usuario_id;
    }
    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }
    public function getAccion() {
        return $this->accion;
    }
    public function setAccion($accion) {
        $this->accion = $accion;
    }
    public function getTablaAfectada() {
        return $this->tabla_afectada;
    }
    public function setTablaAfectada($tabla_afectada) {
        $this->tabla_afectada = $tabla_afectada;
    }
    public function getRegistroId() {
        return $this->registro_id;
    }
    public function setRegistroId($registro_id) {
        $this->registro_id = $registro_id;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function getFecha() {
        return $this->fecha;
    }
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
}
?>