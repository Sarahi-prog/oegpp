<?php
    require_once './config/DB.php';
    require_once 'Cursos.php';

    class CursosModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }
        public function cargar(){
            $sql = "SELECT * FROM obtener_cursos();";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $cursos=array();
            foreach($filas as $f){
                $cur = new Cursos();
                $cur->setIdCurso($f[0]);
                $cur->setCodigoCurso($f[1]);
                $cur->setNombreCurso($f[2]);
                $cur->setTipo($f[3]);
                $cur->setHorasTotales($f[4]);
                array_push($cursos, $cur);
            }
            return $cursos;
        }

        public function cargarD(){
            $sql = "SELECT * FROM obtener_cursos_diplomados();";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $cursos=array();
            foreach($filas as $f){
                $cur = new Cursos();
                $cur->setIdCurso($f[0]);
                $cur->setCodigoCurso($f[1]);
                $cur->setNombreCurso($f[2]);
                $cur->setTipo($f[3]);
                $cur->setHorasTotales($f[4]);
                array_push($cursos, $cur);
            }
            return $cursos;
        }
        public function cargarC(){
            $sql = "SELECT * FROM obtener_cursos_cursos();";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $cursos=array();
            foreach($filas as $f){
                $cur = new Cursos();
                $cur->setIdCurso($f[0]);
                $cur->setCodigoCurso($f[1]);
                $cur->setNombreCurso($f[2]);
                $cur->setTipo($f[3]);
                $cur->setHorasTotales($f[4]);
                array_push($cursos, $cur);
            }
            return $cursos;
        }
        public function guardar(Cursos $curso){
            $sql = "INSERT INTO cursos ( 
            codigo_curso, 
            nombre_curso, 
            tipo, 
            horas_totales) 
                VALUES (
                :cc,
                :nc,
                :t,
                :ht)";
            $ps=$this->db->prepare($sql);
            $cc= $curso->getCodigoCurso();
            $nc= $curso->getNombreCurso();
            $t= $curso->getTipo();
            $ht= $curso->getHorasTotales();
            $ps->bindParam(":cc", $cc);
            $ps->bindParam(":nc", $nc);
            $ps->bindParam(":t", $t);
            $ps->bindParam(":ht", $ht);
            $ps->execute();
        }

        public function modificar(Cursos $curso){
            $sql = "UPDATE cursos SET 
                codigo_curso=:cc, 
                nombre_curso=:nc, 
                tipo=:t, 
                horas_totales=:ht
                WHERE id_curso=:id";
            $ps=$this->db->prepare($sql);
            $id= $curso->getIdCurso();
            $cc= $curso->getCodigoCurso();
            $nc= $curso->getNombreCurso();
            $t= $curso->getTipo();
            $ht= $curso->getHorasTotales();

            $ps->bindParam(":id", $id);
            $ps->bindParam(":cc", $cc);
            $ps->bindParam(":nc", $nc);
            $ps->bindParam(":t", $t);
            $ps->bindParam(":ht", $ht);
            $ps->execute();
        }
    }
?>