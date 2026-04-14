<?php
    require_once './config/DB.php';
    require_once 'NotasModulo.php';

    class NotasModuloModel{
        private $db;
        
        public function __construct(){
            $this->db=DB::conectar();
        }

        public function cargar(){
            $sql = "SELECT nm.*, 
                           c.nombres || ' ' || c.apellidos AS nombre_cliente,
                           m.nombre_modulo
                    FROM notas_modulo nm
                    LEFT JOIN clientes c ON nm.cliente = c.id_cliente
                    LEFT JOIN modulos m ON nm.modulo_id = m.id_modulo
                    ORDER BY nm.id_nota DESC";
                    
            $ps = $this->db->prepare($sql);
            $ps->execute();
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        }

        public function buscar($texto, $campo = null){
            $texto = trim($texto);
            if ($texto === '') {
                return $this->cargar();
            }
            $allowedFields = ['cliente', 'modulo_id', 'nota', 'fecha_registro'];
            if ($campo !== null && in_array($campo, $allowedFields, true)) {
                $sql = "SELECT * FROM notas_modulo WHERE $campo::text LIKE :q";
            } else {
                $sql = "SELECT * FROM notas_modulo
                    WHERE cliente::text LIKE :q
                       OR modulo_id::text LIKE :q
                       OR nota::text LIKE :q
                       OR fecha_registro::text LIKE :q";
            }
            $ps = $this->db->prepare($sql);
            $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
            $ps->execute();
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function modificar(NotasModulo $notas){
            $sql = "UPDATE notas_modulo SET 
                cliente=:cid, 
                modulo_id=:mid, 
                nota=:nt
                WHERE id_nota=:id";
            $ps=$this->db->prepare($sql);
            
            $id = $notas->getIdNota();
            $cid = $notas->getCliente();
            $mid = $notas->getModuloId();
            $nt = $notas->getNota();
            
            $ps->bindParam(":id", $id);
            $ps->bindParam(":cid", $cid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);
            $ps->execute();
        }
        
        public function guardar(NotasModulo $notas){
            $cid = $notas->getCliente();
            $mid = $notas->getModuloId();
            $nt  = $notas->getNota();
            $fr  = $notas->getFechaRegistro();

            if ($fr !== null && $fr !== '') {
                $sql = "INSERT INTO notas_modulo (
                    cliente,
                    modulo_id,
                    nota,
                    fecha_registro
                ) VALUES (
                    :cid,
                    :mid,
                    :nt,
                    :fr
                )";
            } else {
                $sql = "INSERT INTO notas_modulo (
                    cliente,
                    modulo_id,
                    nota
                ) VALUES (
                    :cid,
                    :mid,
                    :nt
                )";
            }

            $ps = $this->db->prepare($sql);
            $ps->bindParam(":cid", $cid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);

            if ($fr !== null && $fr !== '') {
                $ps->bindParam(":fr", $fr);
            }

            $ps->execute();
        }
    }   
?>