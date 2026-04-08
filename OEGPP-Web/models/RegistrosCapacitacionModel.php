    <?php  
        require_once './config/DB.php';
        require_once 'RegistrosCapacitacion.php';

        class RegistrosCapacitacionModel{
            private $db;
            public function __construct(){
                $this->db=DB::conectar();
            }
            public function cargar(){
                $sql = "SELECT * FROM obtener_registrocapacitacion()";
                $ps=$this->db->prepare($sql);
                $ps->execute();
                $filas=$ps->fetchall();
                $registroscapacitacion=array();
                foreach($filas as $f){
                    $regc = new RegistrosCapacitacion();
                    $regc->setIdRegistro($f[0]);
                    $regc->setTrabajadorId($f[1]);
                    $regc->setCursoId($f[2]);
                    $regc->setLibroId($f[3]);
                    $regc->setRegistro($f[4]);
                    $regc->setHorasRealizadas($f[5]);
                    $regc->setFechaInicio($f[6]);
                    $regc->setFechaFin($f[7]);
                    $regc->setFechaEmision($f[8]);
                    $regc->setFolio($f[9]);
                    array_push($registroscapacitacion, $regc);
                }
                return $registroscapacitacion;
            }

            public function buscar($texto, $campo = null){
                $texto = trim($texto);
                if ($texto === '') {
                    return $this->cargar();
                }
                $allowedFields = ['trabajador_id', 'curso_id', 'libro_id', 'registro', 'horas_realizadas', 'fecha_inicio', 'fecha_fin', 'fecha_emision', 'folio'];
                if ($campo !== null && in_array($campo, $allowedFields, true)) {
                    $sql = "SELECT * FROM obtener_registrocapacitacion() WHERE $campo LIKE :q";
                } else {
                    $sql = "SELECT * FROM obtener_registrocapacitacion()
                        WHERE trabajador_id::text LIKE :q
                           OR curso_id::text LIKE :q
                           OR libro_id::text LIKE :q
                           OR registro LIKE :q
                           OR horas_realizadas::text LIKE :q
                           OR fecha_inicio::text LIKE :q
                           OR fecha_fin::text LIKE :q
                           OR fecha_emision::text LIKE :q
                           OR folio LIKE :q";
                }
                $ps = $this->db->prepare($sql);
                $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
                $ps->execute();
                $filas=$ps->fetchall();
                $registroscapacitacion=array();
                foreach($filas as $f){
                    $regc = new RegistrosCapacitacion();
                    $regc->setIdRegistro($f[0]);
                    $regc->setTrabajadorId($f[1]);
                    $regc->setCursoId($f[2]);
                    $regc->setLibroId($f[3]);
                    $regc->setRegistro($f[4]);
                    $regc->setHorasRealizadas($f[5]);
                    $regc->setFechaInicio($f[6]);
                    $regc->setFechaFin($f[7]);
                    $regc->setFechaEmision($f[8]);
                    $regc->setFolio($f[9]);
                    array_push($registroscapacitacion, $regc);
                }
                return $registroscapacitacion;
            }

            public function guardar(RegistrosCapacitacion $registroscapacitacion){
                $sql = "INSERT INTO registros_capacitacion ( 
                trabajador_id, 
                curso_id,
                libro_id, 
                registro, 
                horas_realizadas,
                fecha_inicio, 
                fecha_fin, 
                fecha_emision, 
                folio)
                    VALUES (
                    :tid,
                    :cid,
                    :lid,
                    :r,
                    :hr,
                    :fi,
                    :ff,
                    :fe,
                    :f)";
                $ps=$this->db->prepare($sql);
                $tid= $registroscapacitacion->getTrabajadorId();
                $cid= $registroscapacitacion->getCursoId();
                $lid= $registroscapacitacion->getLibroId();
                $r= $registroscapacitacion->getRegistro();
                $hr= $registroscapacitacion->getHorasRealizadas();
                $fi= $registroscapacitacion->getFechaInicio();
                $ff= $registroscapacitacion->getFechaFin();
                $fe= $registroscapacitacion->getFechaEmision();
                $f= $registroscapacitacion->getFolio();
                
                $ps->bindParam(":tid", $tid);
                $ps->bindParam(":cid", $cid);
                $ps->bindParam(":lid", $lid);
                $ps->bindParam(":r", $r);
                $ps->bindParam(":hr", $hr);
                $ps->bindParam(":fi", $fi);
                $ps->bindParam(":ff", $ff);
                $ps->bindParam(":fe", $fe);
                $ps->bindParam(":f", $f);
                $ps->execute();
            }

            public function modificar(RegistrosCapacitacion $registroscapacitacion){
                $sql = "UPDATE registros_capacitacion SET 
                    trabajador_id=:tid, 
                    curso_id=:cid, 
                    libro_id=:lid, 
                    registro=:r, 
                    horas_realizadas=:hr, 
                    fecha_inicio=:fi, 
                    fecha_fin=:ff, 
                    fecha_emision=:fe, 
                    folio=:f
                    WHERE id_registro=:id";
                $ps=$this->db->prepare($sql);
                $tid= $registroscapacitacion->getTrabajadorId();
                $cid= $registroscapacitacion->getCursoId();
                $lid= $registroscapacitacion->getLibroId();
                $r= $registroscapacitacion->getRegistro();
                $hr= $registroscapacitacion->getHorasRealizadas();
                $fi= $registroscapacitacion->getFechaInicio();
                $ff= $registroscapacitacion->getFechaFin();
                $fe= $registroscapacitacion->getFechaEmision();
                $f= $registroscapacitacion->getFolio();
                $ps->bindParam(":id", $registroscapacitacion->getIdRegistro());
                $ps->bindParam(":tid", $tid);
                $ps->bindParam(":cid", $cid);
                $ps->bindParam(":lid", $lid);
                $ps->bindParam(":r", $r);
                $ps->bindParam(":hr", $hr);
                $ps->bindParam(":fi", $fi);
                $ps->bindParam(":ff", $ff);
                $ps->bindParam(":fe", $fe);
                $ps->bindParam(":f", $f);
                $ps->execute();
            }
        }
    ?>