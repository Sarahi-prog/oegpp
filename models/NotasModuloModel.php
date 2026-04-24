<?php
    require_once './config/DB.php';
    require_once 'NotasModulo.php';

    class NotasModuloModel{
        private $db;
        public function __construct(){
            $this->db=DB::conectar();
        }

            public function cargar() {
                try {
                    $modelNotas = new NotasModuloModel();
                    $modelCursos = new CursosModel();

                    // Cargar todos los cursos (para el selector)
                    $cursos = $modelCursos->cargar();

                    // Capturar curso seleccionado
                    $cursoSeleccionado = isset($_GET['curso_id']) ? $_GET['curso_id'] : null;

                    // Inicializar pivotes
                    $pivotes = [];

                    if (!empty($cursoSeleccionado)) {
                        // Cargar pivote completo: curso + módulos + clientes + notas
                        $pivotes = $modelNotas->cargarPivotPorCurso($cursoSeleccionado);
                    }

                    require './views/notas.php';
                } catch (Exception $e) {
                    Logger::error($e);
                }
            }


            public function cargarPivotPorCurso($cursoId) {
                $sql = "SELECT nm.id_nota, nm.cliente_id, 
                            c.id_curso, c.nombre_curso,
                            m.id_modulo, m.nombre_modulo,
                            cl.id_cliente, cl.nombres || ' ' || cl.apellidos AS cliente_nombre,
                            nm.nota, nm.fecha_registro
                        FROM notas_modulo nm
                        INNER JOIN modulos m ON nm.modulo_id = m.id_modulo
                        INNER JOIN cursos c ON m.curso_id = c.id_curso
                        INNER JOIN clientes cl ON nm.cliente_id = cl.id_cliente
                        WHERE c.id_curso = :cid";
                $ps = $this->db->prepare($sql);
                $ps->bindParam(":cid", $cursoId, PDO::PARAM_INT);
                $ps->execute();
                $filas = $ps->fetchAll(PDO::FETCH_ASSOC);

                $pivotes = [];
                foreach ($filas as $f) {
                    $pivot = new NotaPivot();
                    $pivot->setIdNota($f['id_nota']);
                    $pivot->setCursoId($f['id_curso']);
                    $pivot->setCursoNombre($f['nombre_curso']);
                    $pivot->setModuloId($f['id_modulo']);
                    $pivot->setModuloNombre($f['nombre_modulo']);
                    $pivot->setClienteId($f['cliente_id']);
                    $pivot->setClienteNombre($f['cliente_nombre']);
                    $pivot->setNota($f['nota']);
                    $pivot->setFechaRegistro($f['fecha_registro']);
                    $pivotes[] = $pivot;
                }
                return $pivotes;
            }

            public function cargarPorCurso($cursoId) {
                $sql = "SELECT nm.id_nota, nm.cliente_id, nm.modulo_id, nm.nota, nm.fecha_registro 
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


        public function modificar(NotasModulo $notas){
            $sql = "UPDATE notas_modulo SET 
                trabajador_id=:tid, 
                modulo_id=:mid, 
                nota=:nt
                WHERE id_notas=:id";
            $ps=$this->db->prepare($sql);
            $id= $notas->getIdNota();
            $tid= $notas->getTrabajadorId();
            $mid= $notas->getModuloId();
            $nt= $notas->getNota();
            $ps->bindParam(":id", $id);
            $ps->bindParam(":tid", $tid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);
            $ps->execute();
        }
        public function guardar(NotasModulo $notas){
            $sql = "INSERT INTO notas_modulo ( 
            trabajador_id, 
            modulo_id, 
            nota)
                VALUES (
                :tid,
                :mid,
                :nt)";
            $ps=$this->db->prepare($sql);
            $tid= $notas->getTrabajadorId();
            $mid= $notas->getModuloId();
            $nt= $notas->getNota();
            $ps->bindParam(":tid", $tid);
            $ps->bindParam(":mid", $mid);
            $ps->bindParam(":nt", $nt);
            $ps->execute();
        }
    }   
?>
