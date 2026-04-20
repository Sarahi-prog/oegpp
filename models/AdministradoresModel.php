<?php
// models/AdministradoresModel.php
require_once __DIR__ . '/../config/DB.php';
require_once __DIR__ . '/Administradores.php';

class AdministradoresModel {
    private $db;
    
    public function __construct() {
        $this->db = DB::conectar();
    }
    
    public function cargar() {
        $sql = "SELECT id_admin, usuario, correo, password, rol, verificado FROM administradores";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        $filas = $ps->fetchall();
        $administradores = array();
        foreach($filas as $f){
            $adm = new Administradores();
            $adm->setid_admin($f[0]);
            $adm->setUsuario($f[1]);
            $adm->setCorreo($f[2]);
            $adm->setPassword($f[3]);
            $adm->setRol($f[4]);
            $adm->setVerificado($f[5]);
            array_push($administradores, $adm);
        }
        return $administradores;
    }

    public function modificar(Administradores $administradores) {
        $sql = "UPDATE administradores SET
        usuario = :u,
        correo = :c,
        password = :p,
        rol = :r,
        verificado = :v
        WHERE id_admin = :id";
        $ps = $this->db->prepare($sql);
        $u = $administradores->getUsuario();
        $c = $administradores->getCorreo();
        $p = $administradores->getPassword();
        $r = $administradores->getRol();
        $v = $administradores->getVerificado();
        $id = $administradores->getid_admin();
        $ps->bindParam(":u", $u);
        $ps->bindParam(":c", $c);
        $ps->bindParam(":p", $p);
        $ps->bindParam(":r", $r);
        $ps->bindParam(":v", $v);
        $ps->bindParam(":id", $id);
        $ps->execute();
    }

    public function guardar($usuario, $correo, $password) {
        try {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO administradores (usuario, correo, password, rol, verificado) 
                    VALUES (:u, :c, :p, :r, :v)";
            $ps = $this->db->prepare($sql);
            $ps->bindParam(":u", $usuario);
            $ps->bindParam(":c", $correo);
            $ps->bindParam(":p", $password);
            $ps->bindParam(":r", $rol = 'administrador');
            $ps->bindParam(":v", $verificado = 0); //GUARDA COMO ENTERO 0 ES MAS PATIBLE
            return $ps->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar administrador: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para verificar credenciales (LOGIN)
    public function verificarCredenciales($usuario, $password) {
        $usuario = trim($usuario);
        $password = trim($password);
        

        $sql = "SELECT * FROM administradores 
                WHERE LOWER(usuario) = LOWER(:usuario) OR LOWER(correo) = LOWER(:usuario) LIMIT 1";
        
        $ps = $this->db->prepare($sql);
        $ps->bindParam(":usuario", $usuario);
        $ps->execute();
        $admin = $ps->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return false;
        }

        // 1. Intenta con password_verify (si está hasheada)
        if (password_verify($password, $admin['password'])) {
            return $admin;
        }

        // 2. Intenta comparación directa (si es 123456 literal)
        if ($password === trim($admin['password'])) {
            return $admin;
        }

        return false;
    }

    public function eliminar($id_admin) {
        $sql = "DELETE FROM administradores WHERE id_admin = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(":id", $id_admin);
        $ps->execute();
    }

    public function aprobar($id) {
        try {
            // Actualizamos el campo 'verificado' a true (o 1 en PostgreSQL/MySQL)
            $sql = "UPDATE administradores SET verificado = true WHERE id_admin = :id";
            $ps = $this->db->prepare($sql);
            $ps->bindParam(":id", $id);
            return $ps->execute();
        } catch (PDOException $e) {
            error_log("Error al aprobar admin: " . $e->getMessage());
            return false;
        }
    }
}
?>
