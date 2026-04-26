<?php
require_once './models/CursosModel.php';
require_once './models/Cursos.php';

class CursosController {
    private $model;

    public function __construct() {
        $this->model = new CursosModel();
    }

    // Carga todos los cursos
    public function cargar() {
        $cursos = $this->model->cargarCurso();
        require './views/cursos.php';
    }

    // LLAMADO A DIPLOMADOS (cargarD)
    public function cargarD() {
        $cursos = $this->model->cargarD();
        require './views/cursos.php'; // Usa la misma vista o una específica
    }

    // LLAMADO A CERTIFICADOS (cargarC)
    public function cargarC() {
        $cursos = $this->model->cargarC();
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