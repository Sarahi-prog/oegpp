<?php
class IntentosLogin {
    private $id;
    private $ip;
    private $usuario;
    private $fecha;
    private $exitoso;

    // --- Getters ---
    public function getId() {
        return $this->id;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getExitoso() {
        return $this->exitoso;
    }

    // --- Setters ---
    public function setId($id) {
        $this->id = $id;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setExitoso($exitoso) {
        $this->exitoso = $exitoso;
    }
}
?>
