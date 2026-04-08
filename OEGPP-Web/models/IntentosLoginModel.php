<?php
require_once 'IntentosLogin.php';
require_once './config/DB.php';

class IntentosLoginModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function guardar(IntentosLogin $intentoslogin) {
        $sql = "INSERT INTO intentos_login (ip, usuario, exitoso) 
                VALUES (:ip, :usuario, :exitoso)";
        $ps = $this->db->prepare($sql);

        $ip = $intentoslogin->getIp();
        $usuario = $intentoslogin->getUsuario();
        $exitoso = $intentoslogin->getExitoso();

        $ps->bindParam(':ip', $ip, PDO::PARAM_STR);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ps->bindParam(':exitoso', $exitoso, PDO::PARAM_BOOL);

        return $ps->execute();
    }

    // --- Verificar intentos fallidos por IP en 24h ---
    public function verificar($ip) {
        $sql = "SELECT COUNT(*) as total 
                FROM intentos_login 
                WHERE ip = :ip 
                  AND exitoso = FALSE 
                  AND fecha > NOW() - INTERVAL '24 hours'";
        $ps = $this->db->prepare($sql); 
        $ps->bindParam(':ip', $ip, PDO::PARAM_STR);
        $ps->execute();
        return $ps->fetch(PDO::FETCH_ASSOC); // devuelve ['total' => X]
    }

    // --- Verificar intentos fallidos por usuario en 24h ---
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
}
?>
