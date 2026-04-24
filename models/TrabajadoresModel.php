    <?php  
        require_once './config/DB.php';
        require_once 'Trabajadores.php';

        class TrabajadoresModel{
            private $db;
            public function __construct(){
                $this->db=DB::conectar();
            }

            public function cargar(){
                $sql = "SELECT id_cliente, dni, nombres, apellidos, correo, celular, area, estado FROM clientes";
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
                    $fam->setCelular($f[5]);
                    $fam->setArea($f[6]);
                    $fam->setEstado($f[7]);
                    array_push($trabajadores, $fam);
                }
                return $trabajadores;
            }
            public function buscar($texto, $campo = null){
                $texto = trim($texto);
                if ($texto === '') {
                    return $this->cargar();
                }
                $allowedFields = ['id_cliente', 'dni', 'nombres y apellidos', 'correo', 'celular', 'area'];
                if ($campo !== null && in_array($campo, $allowedFields, true)) {
                    $sql = "SELECT id_cliente, dni, concat(nombres, ' ', apellidos) AS nombres_apellidos, correo, celular, area FROM clientes WHERE $campo LIKE :q";
                } else {
                    $sql = "SELECT id_cliente, dni, concat(nombres, ' ', apellidos) AS nombres_apellidos, correo, celular, area FROM clientes
                        WHERE id_cliente::text LIKE :q
                           OR dni LIKE :q
                           OR concat(nombres, ' ', apellidos) LIKE :q
                           OR correo LIKE :q
                           OR celular LIKE :q
                           OR area LIKE :q";
                }
                $ps=$this->db->prepare($sql);
                $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
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
                    $fam->setCelular($f[5]);
                    $fam->setArea($f[6]);
                    $fam->setEstado($f[7]);
                    array_push($trabajadores, $fam);
                }
                return $trabajadores;
            }

            public function eliminar($id) {
                $sql = "DELETE FROM clientes WHERE id_cliente = :id";
                $ps = $this->db->prepare($sql);
                $ps->bindParam(":id", $id, PDO::PARAM_INT);
                $ps->execute();
            }

            public function actualizar(Trabajadores $trabajador){
                $sql = "UPDATE clientes SET 
                dni=:dni, 
                nombres=:nom, 
                apellidos=:ape, 
                correo=:cor,
                celular=:cel,
                area=:are
                WHERE id_cliente=:id";
                $ps=$this->db->prepare($sql);
                $id= $trabajador->getIdTrabajador();
                $dni= $trabajador->getDni();
                $nom= $trabajador->getNombres();
                $ape= $trabajador->getApellidos();
                $cor= $trabajador->getCorreo();
                $cel= $trabajador->getCelular();
                $are= $trabajador->getArea();
                $ps->bindParam(":id", $trabajador->getIdTrabajador());
                $ps->bindParam(":dni", $trabajador->getDni());
                $ps->bindParam(":nom", $trabajador->getNombres());
                $ps->bindParam(":ape", $trabajador->getApellidos());
                $ps->bindParam(":cor", $trabajador->getCorreo());
                $ps->bindParam(":cel", $trabajador->getCelular());
                $ps->bindParam(":are", $trabajador->getArea());
                $ps->execute();
            }
            public function guardar(Trabajadores $trabajador){
                $sql = "INSERT INTO clientes 
                (dni, 
                nombres, 
                apellidos, 
                correo, 
                celular, 
                area,
                estado) 
                VALUES 
                (:dni, 
                :nom, 
                :ape, 
                :cor, 
                :cel, 
                :are,
                :est)";
                $ps=$this->db->prepare($sql);
                $dni= $trabajador->getDni();
                $nom= $trabajador->getNombres();
                $ape= $trabajador->getApellidos();
                $cor= $trabajador->getCorreo();
                $cel= $trabajador->getCelular();
                $are= $trabajador->getArea();
                $est= $trabajador->getEstado();
                $ps->bindParam(":dni", $dni);
                $ps->bindParam(":nom", $nom);
                $ps->bindParam(":ape", $ape);
                $ps->bindParam(":cor", $cor);
                $ps->bindParam(":cel", $cel);
                $ps->bindParam(":are", $are);
                $ps->bindParam(":est", $est);
                $ps->execute();
            }
        }
    ?>