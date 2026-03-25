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
                    $regc->setIdRegistroCapacitacion($f[0]);
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
                $ps->bindParam(":tid", $registroscapacitacion->getTrabajadorId());
                $ps->bindParam(":cid", $registroscapacitacion->getCursoId());
                $ps->bindParam(":lid", $registroscapacitacion->getLibroId());
                $ps->bindParam(":r", $registroscapacitacion->getRegistro());
                $ps->bindParam(":hr", $registroscapacitacion->getHorasRealizadas());
                $ps->bindParam(":fi", $registroscapacitacion->getFechaInicio());
                $ps->bindParam(":ff", $registroscapacitacion->getFechaFin());
                $ps->bindParam(":fe", $registroscapacitacion->getFechaEmision());
                $ps->bindParam(":f", $registroscapacitacion->getFolio());
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
                $ps->bindParam(":id", $registroscapacitacion->getIdRegistroCapacitacion());
                $ps->bindParam(":tid", $registroscapacitacion->getTrabajadorId());
                $ps->bindParam(":cid", $registroscapacitacion->getCursoId());
                $ps->bindParam(":lid", $registroscapacitacion->getLibroId());
                $ps->bindParam(":r", $registroscapacitacion->getRegistro());
                $ps->bindParam(":hr", $registroscapacitacion->getHorasRealizadas());
                $ps->bindParam(":fi", $registroscapacitacion->getFechaInicio());
                $ps->bindParam(":ff", $registroscapacitacion->getFechaFin());
                $ps->bindParam(":fe", $registroscapacitacion->getFechaEmision());
                $ps->bindParam(":f", $registroscapacitacion->getFolio());
                $ps->execute();
            }
        }
    ?>