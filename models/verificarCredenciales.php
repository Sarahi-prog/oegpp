<?php
class VerificadorCredenciales {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function verificarCredenciales($usuario, $password) {
    // Buscar por usuario o correo
    $sql = "SELECT * FROM administradores WHERE usuario = :usuario OR correo = :usuario";
    $ps = $this->db->prepare($sql);
    $ps->bindParam(":usuario", $usuario);
    $ps->execute();
    $admin = $ps->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        return $admin;
    }
    
    return false;
    }
}