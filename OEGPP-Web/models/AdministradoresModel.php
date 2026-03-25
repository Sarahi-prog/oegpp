<?php
    require_once './config/DB.php';
    require_once 'Administradores.php';

    class AdministradoresModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }
        public function cargar(){
            $sql = "SELECT * FROM obtener_administradores()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $administradores=array();
            foreach($filas as $f){
                $adm = new Administradores();
                $adm->setIdAdministrador($f[0]);
                $adm->setUsuario($f[1]);
                $adm->setPassword($f[2]);
                $adm->setCorreo($f[3]);
                $adm->setVerificado($f[4]);
                $adm->setRol($f[5]);
                array_push($administradores, $adm);
            }
            return $administradores;
        }

                public function cargarsinR(){
            $sql = "SELECT * FROM obtener_administradores_sin_R()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $administradores=array();
            foreach($filas as $f){
                $adm = new Administradores();
                $adm->setIdAdministrador($f[0]);
                $adm->setUsuario($f[1]);
                $adm->setPassword($f[2]);
                $adm->setCorreo($f[3]);
                array_push($administradores, $adm);
            }
            return $administradores;
        }

        public function modificar(Administradores $administradores){
            $sql = "UPDATE administradores SET
            usuario = :u,
            password = :p,
            correo = :c,
            verificado = :v,
            rol = :r
            WHERE id_administrador = :id";
            $ps=$this->db->prepare($sql);
            $ps->bindParam(":u", $administradores->getUsuario());
            $ps->bindParam(":p", $administradores->getPassword());
            $ps->bindParam(":c", $administradores->getCorreo());
            $ps->bindParam(":v", $administradores->getVerificado());
            $ps->bindParam(":r", $administradores->getRol());
            $ps->bindParam(":id", $administradores->getIdAdministrador());
            $ps->execute();
        }

        public function guardar(Administradores $administradores){
            $sql = "INSERT INTO administradores (usuario, password, correo, verificado, rol) VALUES (:u, :p, :c, :v, :r)";
            $ps=$this->db->prepare($sql);
            $ps->bindParam(":u", $administradores->getUsuario());
            $ps->bindParam(":p", $administradores->getPassword());
            $ps->bindParam(":c", $administradores->getCorreo());
            $ps->bindParam(":v", $administradores->getVerificado());
            $ps->bindParam(":r", $administradores->getRol());
            $ps->execute();
        }
    }
?>