<?php
class ErrorLogs {
    private $id;
    private $usuario_id;
    private $mensaje;
    private $tipo;
    private $archivo;
    private $linea;
    private $fecha;
    private $stack_trace;

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
    public function getMensaje() {
        return $this->mensaje;
    }
    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    public function getArchivo() {
        return $this->archivo;
    }
    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }
    public function getLinea() {
        return $this->linea;
    }
    public function setLinea($linea) {
        $this->linea = $linea;
    }
    public function getFecha() {
        return $this->fecha;
    }
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    public function getStackTrace(){
        return $this->stack_trace;
    }
    public function setStackTrace($stack_trace){
        $this->stack_trace = $stacktrace
    }
}
?>