<?php
class Administradores {
    private $id_admin;
    private $usuario;
    private $password;
    private $correo;
    private $verificado;
    private $rol;

    public function getid_admin() {
        return $this->id_admin;
    }
    public function setid_admin($id_admin) {
        $this->id_admin = $id_admin;
    }
    public function getUsuario() {
        return $this->usuario;
    }
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    public function getPassword() {
        return $this->password;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function getCorreo() {
        return $this->correo;
    }
    public function setCorreo($correo) {
        $this->correo = $correo;
    }
    public function getVerificado() {
        return $this->verificado;
    }
    public function setVerificado($verificado) {
        $this->verificado = $verificado;
    }
    public function getRol() {
        return $this->rol;
    }
    public function setRol($rol) {
        $this->rol = $rol;
    }
}
?>