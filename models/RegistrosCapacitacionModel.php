    <?php  
        require_once './config/DB.php';
        require_once 'RegistrosCapacitacion.php';
        class RegistroCapacitacionModel{
            private $db;
            public function __construct(){
                $this->db=DB::conectar();
            }
            public function cargar(){
                $sql = "SELECT 
                        rc.id_registro,
                        (cl.nombres || ' ' || cl.apellidos) AS nombre_trabajador,
                        cs.nombre_curso AS nombre_curso,
                        ('OEGPP-L' || lb.numero_libro) AS nombre_libro,
                        (cs.codigo_curso||'-'||rc.registro),
                        rc.horas_realizadas,
                        rc.fecha_inicio,
                        rc.fecha_fin,
                        rc.fecha_emision,
                        rc.folio,
                        rc.estado,
                        rc.linkr,
                        rc.qr,
                        rc.entregado,
                        rc.entregadopor
                    FROM registros_capacitacion rc
                    INNER JOIN clientes cl 
                        ON rc.clientes_id = cl.id_cliente
                    INNER JOIN cursos cs 
                        ON rc.curso_id = cs.id_curso
                    INNER JOIN libros_registro lb 
                        ON rc.libro_id = lb.id_libro
                    ORDER BY rc.id_registro;";
                $ps=$this->db->prepare($sql);
                $ps->execute();
                $filas=$ps->fetchall();
                $registroscapacitacion=array();
                foreach($filas as $f){
                    $regc = new RegistroCapacitacion();
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
                    $regc->setEstado($f[10]);
                    $regc->setLinkr($f[11]);
                    $regc->setQr($f[12]);
                    $regc->setentregado($f[13]);
                    $regc->setEntregadopor($f[14]);
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
            
            public function guardar(RegistroCapacitacion $registrocapacitacion) {
                $sql = "INSERT INTO registros_capacitacion (
                            clientes_id, curso_id, libro_id, registro, horas_realizadas,
                            fecha_inicio, fecha_fin, fecha_emision, folio, estado
                        ) VALUES (:tid, :cid, :lid, :r, :hr, :fi, :ff, :fe, :f, :estado)";
                $ps = $this->db->prepare($sql);
                $ps->bindValue(":tid", $registrocapacitacion->getTrabajadorId());
                $ps->bindValue(":cid", $registrocapacitacion->getCursoId());
                $ps->bindValue(":lid", $registrocapacitacion->getLibroId());
                $ps->bindValue(":r", $registrocapacitacion->getRegistro());
                $ps->bindValue(":hr", $registrocapacitacion->getHorasRealizadas());
                $ps->bindValue(":fi", $registrocapacitacion->getFechaInicio());
                $ps->bindValue(":ff", $registrocapacitacion->getFechaFin());
                $ps->bindValue(":fe", $registrocapacitacion->getFechaEmision());
                $ps->bindValue(":f", $registrocapacitacion->getFolio());
                $ps->bindValue(":estado", $registrocapacitacion->getEstado() ?? 'Activo');

                $ps->execute();
            }

            
            public function modificar(RegistroCapacitacion $registrocapacitacion){
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