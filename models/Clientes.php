<?php
class Clientes {
    private $id_cliente;
    private $dni;
    private $nombres;
    private $apellidos;
    private $correo;
    private $celular;
    private $area;
    private $estado;

    // Constructor opcional para instanciar rápido
    public function __construct($data = []) {
        $this->id_cliente = $data['id_cliente'] ?? null;
        $this->dni = $data['dni'] ?? null;
        $this->nombres = $data['nombres'] ?? null;
        $this->apellidos = $data['apellidos'] ?? null;
        $this->correo = $data['correo'] ?? null;
        $this->celular = $data['celular'] ?? null;
        $this->area = $data['area'] ?? null;
        $this->estado = $data['estado'] ?? null;
    }

    // Getters y Setters
    public function getIdCliente() { return $this->id_cliente; }
    public function setIdCliente($id) { $this->id_cliente = $id; }

    public function getDni() { return $this->dni; }
    public function setDni($dni) { $this->dni = trim($dni); }

    public function getNombres() { return $this->nombres; }
    public function setNombres($n) { $this->nombres = ucwords(strtolower(trim($n))); }

    public function getApellidos() { return $this->apellidos; }
    public function setApellidos($a) { $this->apellidos = ucwords(strtolower(trim($a))); }

    public function getCorreo() { return $this->correo; }
    public function setCorreo($c) { $this->correo = strtolower(trim($c)); }

    public function getCelular() { return $this->celular; }
    public function setCelular($c) { $this->celular = trim($c); }

    public function getArea() { return $this->area; }
    public function setArea($area) { $this->area = trim($area); }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = trim($estado); }
}
?>