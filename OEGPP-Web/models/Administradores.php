<?php
class Administradores {
    private $id_admin;
    private $usuario;
    private $correo;
    private $password;
    private $rol;
    private $verificado;
    private $bloqueado;
    private $fecha_bloqueo;

    // --- Getters ---
    public function getId_admin() { return $this->id_admin; }
    public function getUsuario() { return $this->usuario; }
    public function getCorreo() { return $this->correo; }
    public function getPassword() { return $this->password; }
    public function getRol() { return $this->rol; }
    public function getVerificado() { return $this->verificado; }
    public function getBloqueado() { return $this->bloqueado; }
    public function getFechaBloqueo() { return $this->fecha_bloqueo; }

    // --- Setters ---
    public function setId_admin($id_admin) { $this->id_admin = $id_admin; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setPassword($password) { $this->password = $password; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setVerificado($verificado) { $this->verificado = $verificado; }
    public function setBloqueado($bloqueado) { $this->bloqueado = $bloqueado; }
    public function setFechaBloqueo($fecha) { $this->fecha_bloqueo = $fecha; }
}
?>