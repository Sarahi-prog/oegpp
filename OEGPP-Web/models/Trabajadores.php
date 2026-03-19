<?php
class Trabajador {
    private $id_trabajador;
    private $dni;
    private $nombres;
    private $apellidos;
    private $correo;

    public function __construct($id_trabajador, $dni, $nombres, $apellidos, $correo) {
        $this->id_trabajador = $id_trabajador;
        $this->dni = $dni;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
    }

    // Getters y setters
    public function getIdTrabajador() {
        return $this->id_trabajador;
    }

    public function setIdTrabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function setNombres($nombres) {
        $this->nombres = $nombres;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
}
?>