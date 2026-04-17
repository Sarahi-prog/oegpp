<?php
    require_once './config/DB.php';
    require_once 'LibrosRegistro.php';

    class LibrosRegistroModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }

        public function cargar(){
            $sql = "SELECT * FROM libros_registro";
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
                $lib->setDistrito($f[5]);
                $lib->setProvincia($f[6]);
                $lib->setDescripcion($f[7]);
                array_push($libros, $lib);
            }
            return $libros;
        }

        public function buscar($texto, $campo = null){
            $texto = trim($texto);
            if ($texto === '') {
                return $this->cargar();
            }
            $allowedFields = ['tipo', 'numero_libro', 'distrito', 'provincia', 'descripcion'];
            if ($campo !== null && in_array($campo, $allowedFields, true)) {
                $sql = "SELECT * FROM libros_registro WHERE $campo LIKE :q";
            } 
            else {
                $sql = "SELECT * FROM libros_registro
                    WHERE tipo LIKE :q
                       OR numero_libro LIKE :q
                       OR distrito LIKE :q
                       OR provincia LIKE :q
                       OR descripcion LIKE :q";
            }
            $ps = $this->db->prepare($sql);
            $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
            $ps->execute();
            $filas = $ps->fetchall();
            $libros = array();
            foreach($filas as $f){
                $lib = new LibrosRegistro();
                $lib->setIdLibro($f[0]);
                $lib->setTipo($f[1]);
                $lib->setNumeroLibro($f[2]);
                $lib->setAnioInicio($f[3]);
                $lib->setFechaFin($f[4]);
                $lib->setDistrito($f[5]);
                $lib->setProvincia($f[6]);
                $lib->setDescripcion($f[7]);
                array_push($libros, $lib);
            }
            return $libros;
        }

        public function guardar(LibrosRegistro $libro){
            $sql = "INSERT INTO libros_registro ( 
            tipo, 
            numero_libro, 
            anio_inicio, 
            fecha_fin,
            distrito,
            provincia,
            descripcion) 
                VALUES (
                :t,
                :nl,
                :ai,
                :ff,
                :dt,
                :pv,
                :dc)";
            $ps=$this->db->prepare($sql);
            $t= $libro->getTipo();
            $nl= $libro->getNumeroLibro();
            $ai= $libro->getAnioInicio();
            $ff = $libro->getFechaFin();
            $ff = !empty($ff) ? $ff : null;
            $dt= $libro->getDistrito();
            $pv= $libro->getProvincia();
            $dc= $libro->getDescripcion();

            $ps->bindParam(":t", $t);
            $ps->bindParam(":nl", $nl);
            $ps->bindParam(":ai", $ai);
            $ps->bindParam(":ff", $ff);
            $ps->bindParam(":dt", $dt);
            $ps->bindParam(":pv", $pv);
            $ps->bindParam(":dc", $dc);
            $ps->execute();
        }
        
        public function modificar(LibrosRegistro $libro){
            $sql = "UPDATE libros_registro SET 
            tipo=:t, 
            numero_libro=:nl,
            anio_inicio=:ai, 
            fecha_fin=:ff,
            distrito=:dt,
            provincia=:pv,
            descripcion=:dc
                WHERE id_libro=:id";
            $ps=$this->db->prepare($sql);
            $id= $libro->getIdLibro();
            $t= $libro->getTipo();
            $nl= $libro->getNumeroLibro();
            $ai= $libro->getAnioInicio();
            $ff= $libro->getFechaFin();
            $dt= $libro->getDistrito();
            $pv= $libro->getProvincia();
            $dc= $libro->getDescripcion();
            $ps->bindParam(":id", $id);
            $ps->bindParam(":t", $t);
            $ps->bindParam(":nl", $nl);
            $ps->bindParam(":ai", $ai);
            $ps->bindParam(":ff", $ff);
            $ps->bindParam(":dt", $dt);
            $ps->bindParam(":pv", $pv);
            $ps->bindParam(":dc", $dc);

            $ps->execute();
        }
    }
?>