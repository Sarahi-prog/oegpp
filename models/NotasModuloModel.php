<?php
    require_once './config/DB.php';
    require_once 'NotasModulo.php';

    class NotasModuloModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }

            public function cargar() {
                $sql = "SELECT id_nota, clientes_id, modulo_id, nota, fecha_registro  
                        FROM notas_modulo";
                $ps = $this->db->prepare($sql);
                $ps->execute();
                $filas = $ps->fetchAll(PDO::FETCH_ASSOC);

                $notas = [];
                foreach ($filas as $f) {
                    $nota = new NotasModulo();
                    $nota->setIdNota($f['id_nota']);
                    $nota->setTrabajadorId($f['clientes_id']);
                    $nota->setModuloId($f['modulo_id']);
                    $nota->setNota($f['nota']);
                    $nota->setFechaRegistro($f['fecha_registro']);
                    $notas[] = $nota;
                }
                return $notas;
            }

            public function cargarPorCurso($cursoId) {
                $sql = "SELECT nm.id_nota, nm.cliente_id, nm.modulo_id, nm.nota, nm.fecha_registro 
                        FROM notas_modulo nm
                        INNER JOIN modulos m ON nm.modulo_id = m.id_modulo
                        WHERE m.curso_id = :cid";
                $ps = $this->db->prepare($sql);
                $ps->bindParam(":cid", $cursoId, PDO::PARAM_INT);
                $ps->execute();
                $filas = $ps->fetchAll(PDO::FETCH_ASSOC);

                $notas = [];
                foreach ($filas as $f) {
                    $nota = new NotasModulo();
                    $nota->setIdNota($f['id_nota']);
                    $nota->setTrabajadorId($f['clientes_id']);
                    $nota->setModuloId($f['modulo_id']);
                    $nota->setNota($f['nota']);
                    $nota->setFechaRegistro($f['fecha_registro']);
                    $notas[] = $nota;
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
