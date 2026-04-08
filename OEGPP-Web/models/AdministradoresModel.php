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
    public function validarLogin(Administradores $administradores) {
        $sql = "SELECT * FROM administradores 
                WHERE (correo = :c OR usuario = :u) AND verificado = true";
        $ps = $this->db->prepare($sql);
        $ps->bindValue(':c', $administradores->getCorreo());
        $ps->bindValue(':u', $administradores->getUsuario());
        $ps->execute();
        $row = $ps->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($administradores->getPassword(), $row['password'])) {
            // Verificar bloqueo
            if ($row['bloqueado']) {
                $fechaBloqueo = new DateTime($row['fecha_bloqueo']);
                $ahora = new DateTime();
                $diff = $ahora->diff($fechaBloqueo);

                if ($diff->h < 24) {
                    return false; // sigue bloqueado
                } else {
                    // desbloquear automáticamente
                    $this->desbloquearUsuario($row['usuario']);
                }
            }
            $adm = new Administradores();
            $adm->setId_admin($row['id_admin']);
            $adm->setUsuario($row['usuario']);
            $adm->setCorreo($row['correo']);
            $adm->setVerificado($row['verificado']);
            $adm->setRol($row['rol']);
            $adm->setBloqueado($row['bloqueado']);
            $adm->setFechaBloqueo($row['fecha_bloqueo']);
            return $adm;
        }
        return false;
    }

    // --- Bloquear usuario ---
    public function bloquearUsuario($usuario) {
        $sql = "UPDATE administradores 
                SET bloqueado = TRUE, fecha_bloqueo = NOW() 
                WHERE usuario = :usuario";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ps->execute();
    }

    // --- Desbloquear usuario ---
    public function desbloquearUsuario($usuario) {
        $sql = "UPDATE administradores 
                SET bloqueado = FALSE, fecha_bloqueo = NULL 
                WHERE usuario = :usuario";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ps->execute();
    }

    // --- Verificar estado de bloqueo ---
    public function estaBloqueado($usuario) {
        $sql = "SELECT bloqueado, fecha_bloqueo 
                FROM administradores 
                WHERE usuario = :usuario";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ps->execute();
        return $ps->fetch(PDO::FETCH_ASSOC);
    }

}
?>
