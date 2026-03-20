<?php
class Trabajadores {
    private $id_trabajador;
    private $dni;
    private $nombres;
    private $apellidos;
    private $correo;
    private $celular;
    private $area;


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
    public function getCelular() {
        return $this->celular;
    }
    public function setCelular($celular) {
        $this->celular = $celular;
    }
    public function getArea() {
        return $this->area;
    }
    public function setArea($area) {
        $this->area = $area;
    }
}
?>