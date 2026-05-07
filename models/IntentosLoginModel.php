<?php
require_once 'IntentosLogin.php';
require_once __DIR__ . '/../config/DB.php';

class IntentosLoginModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function guardar(IntentosLogin $intentoslogin) {
        // Nota: Agregamos "fecha = NOW()" si tu DB no lo tiene por defecto
        $sql = "INSERT INTO intentos_login (ip, usuario, exitoso, fecha) 
                VALUES (:ip, :usuario, :exitoso, NOW())";
        $ps = $this->db->prepare($sql);

        $ip = $intentoslogin->getIp();
        $usuario = $intentoslogin->getUsuario();
        $exitoso = $intentoslogin->getExitoso();

        $ps->bindParam(':ip', $ip, PDO::PARAM_STR);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        // PostgreSQL prefiere integers (0/1) para booleanos desde PDO a veces
        $ps->bindValue(':exitoso', $exitoso ? 1 : 0, PDO::PARAM_INT);

        return $ps->execute();
    }

    public function verificarPorUsuario($usuario) {
        $sql = "SELECT COUNT(*) as total 
                FROM intentos_login 
                WHERE usuario = :usuario 
                  AND exitoso = FALSE 
                  AND fecha > NOW() - INTERVAL '24 hours'";
        $ps = $this->db->prepare($sql); 
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ps->execute();
        return $ps->fetch(PDO::FETCH_ASSOC); 
    }

    // --- ESTA ES LA FUNCIÓN PARA LA OPCIÓN B ---
    public function desbloquear($usuario) {
        $sql = "DELETE FROM intentos_login WHERE usuario = :usuario";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        return $ps->execute();
    }
}