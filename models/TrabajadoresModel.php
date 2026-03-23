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
        }
    ?>