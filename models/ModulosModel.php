<?php
    require_once './config/DB.php';
    require_once 'Modulos.php';

    class ModulosModel{
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
            $sql = "SELECT nm.id_nota, nm.clientes_id, nm.modulo_id, nm.nota, nm.fecha_registro 
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