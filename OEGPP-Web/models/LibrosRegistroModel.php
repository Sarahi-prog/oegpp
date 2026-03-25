<?php
    require_once './config/DB.php';
    require_once 'LibrosRegistro.php';

    class LibrosRegistroModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }

        public function cargar(){
            $sql = "SELECT * FROM obtener_libros_registro()";
            $ps=$this->db->prepare($sql);
            $ps->execute();
            $filas=$ps->fetchall();
            $libros=array();
            foreach($filas as $f){
                $lib = new LibrosRegistro();
                $lib->setIdLibro($f[0]);
                $lib->setTipo($f[1]);
                $lib->setNumeroLibro($f[2]);
                $lib->setAnioInicio($f[3]);
                $lib->setFechaFin($f[4]);
                array_push($libros, $lib);
            }
            return $libros;
        }

        public function guardar(LibrosRegistro $libro){
            $sql = "INSERT INTO libros_registro ( 
            tipo, 
            numero_libro, 
            anio_inicio, 
            fecha_fin) 
                VALUES (
                :t,
                :nl,
                :ai,
                :ff)";
            $ps=$this->db->prepare($sql);
            $ps->bindParam(":t", $libro->getTipo());
            $ps->bindParam(":nl", $libro->getNumeroLibro());
            $ps->bindParam(":ai", $libro->getAnioInicio());
            $ps->bindParam(":ff", $libro->getFechaFin());
            $ps->execute();
        }
        
        public function modificar(LibrosRegistro $libro){
            $sql = "UPDATE libros_registro SET 
            tipo=:t, 
            numero_libro=:nl,
            anio_inicio=:ai, 
            fecha_fin=:ff
                WHERE id_libro=:id";
            $ps=$this->db->prepare($sql);
            $ps->bindParam(":id", $libro->getIdLibro());
            $ps->bindParam(":t", $libro->getTipo());
            $ps->bindParam(":nl", $libro->getNumeroLibro());
            $ps->bindParam(":ai", $libro->getAnioInicio());
            $ps->bindParam(":ff", $libro->getFechaFin());
            $ps->execute();
        }
    }
?>