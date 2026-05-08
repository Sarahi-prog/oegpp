<?php
require_once './models/CursosModel.php';
require_once './models/Cursos.php';

class CursosController {
    private $model;

    public function __construct() {
        $this->model = new CursosModel();
    }

    public function cargarCursos() {
        $cursos = $this->model->cargarCurso();
        require './views/cursos.php';
    }

    // Carga todos los cursos
    public function cargar() {
        $cursos = $this->model->cargar();
        require './views/cursos.php';
    }
    
    public function listarCursos() {
        // Página actual desde GET
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $registrosPorPagina = 20;

        // Llamar al modelo para traer los cursos de esa página
        $cursos = $this->model->obtenerCursosPaginados($pagina, $registrosPorPagina);

        // Calcular total de páginas
        $totalRegistros = $this->model->contarCursos();
        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

        // Pasar datos a la vista
        $pagina_actual = 'cursos';
        require './views/cursos.php';
    }

    public function guardarCursos() {
        // 1. Verificamos que al menos los datos básicos existan
        if (isset($_POST['dni'], $_POST['nombres'])) {
            
            $cursos = $this->mapearDatosFormulario();
                $id_cursos = $_POST['id_cursos'] ?? '';

            if (!empty($id_cursos)) {
                // --- MODO EDICIÓN ---
                $cursos->setIdCursos($id_cursos);
                if ($this->model->modificarCursos($cursos)) {
                    header("Location: index.php?accion=cursos&msg=actualizado");
                    exit();
                } else {
                    $this->manejarError("Error al actualizar");
                }
            } else {
                // --- MODO NUEVO ---
                $idGenerado = $this->model->guardarCursos($cursos);
                if ($idGenerado !== null) {
                    header("Location: index.php?accion=cursos&msg=guardado");
                    exit();
                } else {
                    // Si el error es por DNI duplicado
                    if (strpos($this->model->ultimoError, '23505') !== false) {
                        $this->manejarError("El DNI ya se encuentra registrado.");
                    } else {
                        $this->manejarError("Error al guardar nuevo curso");
                    }
                }
            }
        }
    }
    // LLAMADO A DIPLOMADOS (cargarD)
    public function cargarD() {
        $cursos = $this->model->cargarDiplomados();
        require './views/cursos.php'; // Usa la misma vista o una específica
    }

    // LLAMADO A CERTIFICADOS (cargarC)
    public function cargarC() {
        $cursos = $this->model->cargarCertificados();
        require './views/cursos.php';
    }

    public function modificarCurso() {
        if (isset($_POST['id_curso'])) {
            $curso = new Cursos();
            $curso->setIdCurso($_POST['id_curso']);
            $curso->setCodigoCurso($_POST['codigo_curso']);
            $curso->setNombreCurso($_POST['nombre_curso']);
            $curso->setTipo($_POST['tipo']);
            $curso->setHorasTotales($_POST['horas_totales']);
            $curso->setEstado($_POST['estado']);

            if ($this->model->modificarCurso($curso)) {
                header("Location: index.php?accion=cursos");
            }
        }
    }

    public function guardarCurso() {
        if (isset($_POST['codigo_curso']) && isset($_POST['nombre_curso']) && isset($_POST['tipo']) && isset($_POST['horas_totales'])) {
            $curso = new Cursos();
            $curso->setCodigoCurso($_POST['codigo_curso']);
            $curso->setNombreCurso($_POST['nombre_curso']);
            $curso->setTipo($_POST['tipo']);
            $curso->setHorasTotales($_POST['horas_totales']);
            $curso->setEstado($_POST['estado']);
            if ($this->model->guardarCurso($curso)) {
                header("Location: index.php?accion=cursos");
            }
        } else {
            require './views/cursos.php';
        }
    }

        public function eliminarCurso() {
        if (isset($_GET['id'])) {
            $id_curso = $_GET['id'];

            if ($this->model->eliminarCurso($id_curso)) {
                header("Location: index.php?accion=cursos&res=eliminado");
                exit();
            } else {
                echo "Error: No se pudo eliminar. Es posible que el curso esté referenciado en otra tabla.";
            }
        } else {
            echo "Error: No se recibió un ID válido para eliminar.";
        }
    }


    public function actualizar_estado() {
        if (isset($_POST['id_curso']) && isset($_POST['estado'])) {
            $id = intval($_POST['id_curso']);
            $estado = intval($_POST['estado']); // Recibe 1 o 0

            $exito = $this->model->actualizarEstado($id, $estado);

            header('Content-Type: application/json');
            echo json_encode(['exito' => $exito]);
            exit;
        }
    }

}
?>