<?php
    require_once './config/DB.php';
    require_once 'Modulos.php';

    class ModulosModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }


        public function cargar(){
            $sql = "SELECT * FROM obtener_modulos()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $modulos=array();
            foreach($filas as $f){
                $mod = new Modulos();
                $mod->setIdModulo($f[0]);
                $mod->setCursoId($f[1]);
                $mod->setNombreModulo($f[2]);
                $mod->setHoras($f[3]);
                $mod->setFechaInicio($f[4]);
                $mod->setFechaFin($f[5]);
                array_push($modulos, $mod);
            }
            return $modulos;
        }

        public function guardar(Modulos $modulo){
            $sql = "INSERT INTO modulos ( 
            curso_id, 
            nombre_modulo, 
            horas, 
            fecha_inicio,
            fecha_fin) 
                VALUES (
                :cid,
                :nm,
                :h,
                :fi,
                :ff)";
            $ps=$this->db->prepare($sql);
            $cid= $modulo->getCursoId();
            $nm= $modulo->getNombreModulo();
            $h= $modulo->getHoras();
            $fi= $modulo->getFechaInicio();
            $ff= $modulo->getFechaFin();
            $ps->bindParam(":cid", $cid);
            $ps->bindParam(":nm", $nm);
            $ps->bindParam(":h", $h);
            $ps->bindParam(":fi", $fi);
            $ps->bindParam(":ff", $ff);
            $ps->execute();
        }


        public function modificar(Modulos $modulo){
            $sql = "UPDATE modulos SET 
            curso_id=:cid, 
            nombre_modulo=:nm, 
            horas=:h, 
            fecha_inicio=:fi,
            fecha_fin=:ff
                WHERE id_modulo=:id";
            $ps=$this->db->prepare($sql);
            $id= $modulo->getIdModulo();
            $cid= $modulo->getCursoId();
            $nm= $modulo->getNombreModulo();
            $h= $modulo->getHoras();
            $fi= $modulo->getFechaInicio();
            $ff= $modulo->getFechaFin();
            $ps->bindParam(":id", $id);
            $ps->bindParam(":cid", $cid);
            $ps->bindParam(":nm", $nm);
            $ps->bindParam(":h", $h);
            $ps->bindParam(":fi", $fi);
            $ps->bindParam(":ff", $ff);
            $ps->execute();
        }
    }
?>