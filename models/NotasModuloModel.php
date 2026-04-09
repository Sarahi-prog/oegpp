<?php
    require_once './config/DB.php';
    require_once 'NotasModulo.php';

    class NotasModuloModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }

        public function cargar(){
            $sql = "SELECT * FROM obtener_notas_modulo()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $notas=array();
            foreach($filas as $f){
                $nota = new NotasModulo();
                $nota->setIdNota($f[0]);
                $nota->setTrabajadorId($f[1]);
                $nota->setModuloId($f[2]);
                $nota->setNota($f[3]);
                $nota->setFechaRegistro($f[4]);
                array_push($notas, $nota);
            }
            return $notas;
        }
        public function modificar(NotasModulo $notas){
            $sql = "UPDATE notas_modulo SET 
                trabajador_id=:tid, 
                modulo_id=:mid, 
                nota=:nt
                WHERE id_notas=:id";
            $ps=$this->db->prepare($sql);
            $id= $notas->getIdNota();
            $tid= $notas->getTrabajadorId();
            $mid= $notas->getModuloId();
            $nt= $notas->getNota();
            $ps->bindParam(":id", $id);
            $ps->bindParam(":tid", $tid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);
            $ps->execute();
        }
        public function guardar(NotasModulo $notas){
            $sql = "INSERT INTO notas_modulo ( 
            trabajador_id, 
            modulo_id, 
            nota)
                VALUES (
                :tid,
                :mid,
                :nt)";
            $ps=$this->db->prepare($sql);
            $tid= $notas->getTrabajadorId();
            $mid= $notas->getModuloId();
            $nt= $notas->getNota();
            $ps->bindParam(":tid", $tid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);
            $ps->execute();
        }
    }   
?>
