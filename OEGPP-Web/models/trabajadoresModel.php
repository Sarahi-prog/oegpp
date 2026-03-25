    <?php  
        require_once './config/DB.php';
        require_once 'Trabajadores.php';

        class TrabajadoresModel{
            private $db;
            public function __construct(){
                $this->db=DB::conectar();
            }

            public function cargar(){
                $sql = "SELECT id_trabajador, dni, nombres, apellidos, correo FROM trabajadores";
                $ps=$this->db->prepare($sql);
                $ps->execute();
                $filas=$ps->fetchall();
                $trabajadores=array();
                foreach($filas as $f){
                    $fam = new Trabajadores();
                    $fam->setIdTrabajador($f[0]);
                    $fam->setDni($f[1]);
                    $fam->setNombres($f[2]);
                    $fam->setApellidos($f[3]);
                    $fam->setCorreo($f[4]);
                    array_push($trabajadores, $fam);
                }
                return $trabajadores;
            }
            public function actualizar(Trabajadores trabajador){
                $sql = "UPDATE trabajadores SET 
                dni=:dni, 
                nombres=:nom, 
                apellidos=:ape, 
                correo=:cor,
                celular=:cel,
                area=:are
                WHERE id_trabajador=:id";
                $ps=$this->db->prepare($sql);
                $ps->bindParam(":id", $trabajador->getIdTrabajador());
                $ps->bindParam(":dni", $trabajador->getDni());
                $ps->bindParam(":nom", $trabajador->getNombres());
                $ps->bindParam(":ape", $trabajador->getApellidos());
                $ps->bindParam(":cor", $trabajador->getCorreo());
                $ps->bindParam(":cel", $trabajador->getCelular());
                $ps->bindParam(":are", $trabajador->getArea());
                $ps->execute();
            }
            public function guardar(Trabajadores trabajador){
                $sql = "INSERT INTO trabajadores 
                (dni, 
                nombres, 
                apellidos, 
                correo, 
                celular, 
                area) 
                VALUES 
                (:dni, 
                :nom, 
                :ape, 
                :cor, 
                :cel, 
                :are)";
                $ps=$this->db->prepare($sql);
                $ps->bindParam(":dni", $trabajador->getDni());
                $ps->bindParam(":nom", $trabajador->getNombres());
                $ps->bindParam(":ape", $trabajador->getApellidos());
                $ps->bindParam(":cor", $trabajador->getCorreo());
                $ps->bindParam(":cel", $trabajador->getCelular());
                $ps->bindParam(":are", $trabajador->getArea());
                $ps->execute();
            }
        
        }
    ?>