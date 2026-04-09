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

    public function guardar(Administradores $administradores) {
        $sql = "INSERT INTO administradores (usuario, correo, password, rol, verificado) VALUES (:u, :c, :p, :r, :v)";
        $ps = $this->db->prepare($sql);
        $u = $administradores->getUsuario();
        $c = $administradores->getCorreo();
        $p = $administradores->getPassword();
        $r = $administradores->getRol();
        $v = $administradores->getVerificado();
        $ps->bindParam(":u", $u);
        $ps->bindParam(":c", $c);
        $ps->bindParam(":p", $p);
        $ps->bindParam(":r", $r);
        $ps->bindParam(":v", $v);
        $ps->execute();
    }
    
    // Método para verificar credenciales (LOGIN)
    public function verificarCredenciales($usuario, $password) {
        $sql = "SELECT * FROM administradores WHERE usuario = :usuario";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(":usuario", $usuario);
        $ps->execute();
        $admin = $ps->fetch(PDO::FETCH_ASSOC);
        
        // La contraseña está guardada como texto plano "123456"
        if ($admin && $password === $admin['password']) {
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Eliminar un administrador por ID
     */
    public function eliminar($id_admin) {
        $sql = "DELETE FROM administradores WHERE id_admin = :id";
        $ps = $this->db->prepare($sql);
        $ps->bindParam(":id", $id_admin);
        $ps->execute();
    }
}
?>