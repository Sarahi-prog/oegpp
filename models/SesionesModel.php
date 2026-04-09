<?php
    require_once './config/DB.php';
    require_once 'Sesiones.php';

    class SesionesModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }
        public function cargar(){
            $sql = "SELECT * FROM obtener_sesiones()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $sesiones=array();
            foreach($filas as $f){
                $al = new Sesiones();
                $al->setIdSesion($f[0]);
                $al->setUsuarioId($f[1]);
                $al->setFechaInicio($f[2]);
                $al->setFechaFin($f[3]);
                $al->setActiva($f[4]);
                array_push($sesiones, $al);
            }
            return $sesiones;
        }
        public function guardar(Sesiones $sesiones){
            $sql = "INSERT INTO sesiones ( 
            usuario_id, 
            fecha_inicio, 
            fecha_fin, 
            activa)
                VALUES (
                :usid,
                :fi,
                :ff,
                :ac)";
            $ps=$this->db->prepare($sql);
            $usid= $sesiones->getUsuarioId();
            $fi= $sesiones->getFechaInicio();
            $ff= $sesiones->getFechaFin();
            $ac= $sesiones->getActiva();
            $ps->bindParam(":usid", $usid);
            $ps->bindParam(":fi", $fi);
            $ps->bindParam(":ff", $ff);
            $ps->bindParam(":ac", $ac);
            $ps->execute();
        }
    }
?>