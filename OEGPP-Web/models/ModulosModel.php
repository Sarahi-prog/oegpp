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
            $ps->bindParam(":cid", $modulo->getCursoId());
            $ps->bindParam(":nm", $modulo->getNombreModulo());
            $ps->bindParam(":h", $modulo->getHoras());
            $ps->bindParam(":fi", $modulo->getFechaInicio());
            $ps->bindParam(":ff", $modulo->getFechaFin());
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
            $ps->bindParam(":id", $modulo->getIdModulo());
            $ps->bindParam(":cid", $modulo->getCursoId());
            $ps->bindParam(":nm", $modulo->getNombreModulo());
            $ps->bindParam(":h", $modulo->getHoras());
            $ps->bindParam(":fi", $modulo->getFechaInicio());
            $ps->bindParam(":ff", $modulo->getFechaFin());
            $ps->execute();
        }
    }
?>