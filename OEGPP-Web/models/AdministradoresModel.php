<?php
require_once './config/DB.php';
require_once 'Administradores.php';

class AdministradoresModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    // --- Cargar todos los administradores ---
    public function cargar() {
        $sql = "SELECT * FROM administradores";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        $filas = $ps->fetchAll(PDO::FETCH_ASSOC);
        $administradores = [];

        foreach ($filas as $f) {
            $adm = new Administradores();
            $adm->setId_admin($f['id_admin']);
            $adm->setUsuario($f['usuario']);
            $adm->setPassword($f['password']);
            $adm->setCorreo($f['correo']);
            $adm->setVerificado($f['verificado']);
            $adm->setRol($f['rol']);
            $adm->setBloqueado($f['bloqueado']);
            $adm->setFechaBloqueo($f['fecha_bloqueo']);
            $administradores[] = $adm;
        }
        return $administradores;
    }

    // --- Buscar por verificado ---
    public function busquedaPorVerificado($verificado) {
        $sql = "SELECT * FROM administradores WHERE verificado = :b";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(':b', $verificado, PDO::PARAM_BOOL);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Búsqueda general por texto ---
    public function busquedaGeneral($texto) {
        $sql = "SELECT * FROM administradores
                WHERE usuario LIKE :q OR correo LIKE :q";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(':q', '%' . $texto . '%');
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Guardar nuevo administrador ---
    public function guardar(Administradores $administradores) {
        $sql = "INSERT INTO administradores 
                (usuario, password, correo, verificado, rol, bloqueado, fecha_bloqueo) 
                VALUES (:u, :p, :c, :v, :r, :b, :fb)";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":u", $administradores->getUsuario());
        $ps->bindValue(":p", password_hash($administradores->getPassword(), PASSWORD_DEFAULT));
        $ps->bindValue(":c", $administradores->getCorreo());
        $ps->bindValue(":v", $administradores->getVerificado(), PDO::PARAM_BOOL);
        $ps->bindValue(":r", $administradores->getRol());
        $ps->bindValue(":b", $administradores->getBloqueado(), PDO::PARAM_BOOL);
        $ps->bindValue(":fb", $administradores->getFechaBloqueo());
        $ps->execute();
    }

    // --- Modificar administrador existente ---
    public function modificar(Administradores $administradores) {
        $sql = "UPDATE administradores SET
                usuario = :u,
                password = :p,
                correo = :c,
                verificado = :v,
                rol = :r,
                bloqueado = :b,
                fecha_bloqueo = :fb
                WHERE id_admin = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(":u", $administradores->getUsuario());
        $ps->bindValue(":p", password_hash($administradores->getPassword(), PASSWORD_DEFAULT));
        $ps->bindValue(":c", $administradores->getCorreo());
        $ps->bindValue(":v", $administradores->getVerificado(), PDO::PARAM_BOOL);
        $ps->bindValue(":r", $administradores->getRol());
        $ps->bindValue(":b", $administradores->getBloqueado(), PDO::PARAM_BOOL);
        $ps->bindValue(":fb", $administradores->getFechaBloqueo());
        $ps->bindValue(":id", $administradores->getId_admin());
        $ps->execute();
    }

    // --- Validar login ---
    public function validarLogin($usuario, $contrasena) {
        $sql = "SELECT * FROM administradores 
                WHERE usuario = :usuario AND verificado = 1";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':usuario', $usuario);
        $ps->execute();
        $fila = $ps->fetch(PDO::FETCH_ASSOC);

        if ($fila && password_verify($contrasena, $fila['password'])) {
            $admin = new Administradores();
            $admin->setId_admin($fila['id_admin']);
            $admin->setUsuario($fila['usuario']);
            return $admin;
        }
        return null;
    }

    // --- Bloquear usuario ---
    public function bloquearAdministrador($id_admin) {
        $sql = "UPDATE administradores 
                SET bloqueado = TRUE, fecha_bloqueo = NOW() 
                WHERE id_admin = :id_admin";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':id_admin', $id_admin, PDO::PARAM_STR);
        $ps->execute();
    }

    // --- Desbloquear usuario ---
    public function desbloquearAdministrador($id_admin) {
        $sql = "UPDATE administradores 
                SET bloqueado = FALSE, fecha_bloqueo = NULL 
                WHERE id_admin = :id_admin";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':id_admin', $id_admin, PDO::PARAM_STR);
        $ps->execute();
    }

    // --- Verificar estado de bloqueo ---
    public function estaBloqueado($id_admin) {
        $sql = "SELECT bloqueado, fecha_bloqueo 
                FROM administradores 
                WHERE id_admin = :id_admin";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':id_admin', $id_admin, PDO::PARAM_STR);
        $ps->execute();
        return $ps->fetch(PDO::FETCH_ASSOC);
    }
}
?>
