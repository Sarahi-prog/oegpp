<?php
require_once './models/ProgramaEducativoModel.php';
require_once './models/ProgramaEducativo.php';

class ProgramaEducativoController {

    private $model;

    public function __construct() {
        $this->model = new ProgramaEducativoModel();
    }

    // LISTAR
    public function cargar() {
        $programas = $this->model->cargarProgramaEducativo();
        require './views/programa_educativo.php';
    }

    // DIPLOMADOS
    public function cargarD() {
        $programas = $this->model->cargarD();
        require './views/programa_educativo.php';
    }

    // CERTIFICADOS
    public function cargarC() {
        $programas = $this->model->cargarC();
        require './views/programa_educativo.php';
    }

    // GUARDAR / MODIFICAR
    public function guardarProgramaEducativo() {

        if (
            isset($_POST['codigo']) &&
            isset($_POST['nombre']) &&
            isset($_POST['tipo']) &&
            isset($_POST['horas_totales'])
        ) {

            $programa = new ProgramaEducativo();

            // SI EXISTE ID -> EDICIÓN
            if (!empty($_POST['id_programa'])) {
                $programa->setIdPrograma((int)$_POST['id_programa']);
            }

            $programa->setCodigo($_POST['codigo']);
            $programa->setNombre($_POST['nombre']);
            $programa->setTipo($_POST['tipo']);
            $programa->setHorasTotales((int)$_POST['horas_totales']);
            $programa->setEstado((int)($_POST['estado'] ?? 1));

            // EDITAR
            if (!empty($_POST['id_programa'])) {

                $resultado = $this->model->modificarProgramaEducativo($programa);

                if ($resultado) {
                    header("Location: index.php?accion=programa_educativo&msg=actualizado");
                    exit();
                }

            } else {

                // NUEVO
                $resultado = $this->model->guardarProgramaEducativo($programa);

                if ($resultado) {
                    header("Location: index.php?accion=programa_educativo&msg=guardado");
                    exit();
                }
            }

            $this->manejarError("Error al guardar el programa educativo.");
        }

        $this->manejarError("Faltan datos requeridos.");
    }

    // ELIMINAR
    public function eliminarProgramaEducativo() {

        if (isset($_GET['id'])) {

            $id_programa = $_GET['id'];

            if ($this->model->eliminarProgramaEducativo($id_programa)) {

                header("Location: index.php?accion=programa_educativo&msg=eliminado");
                exit();

            } else {

                echo "Error al eliminar programa educativo.";
            }

        } else {

            echo "ID inválido.";
        }
    }

    // ESTADO
    public function actualizar_estado() {

        if (isset($_POST['id']) && isset($_POST['estado'])) {

            $id = intval($_POST['id']);
            $estado = intval($_POST['estado']);

            $exito = $this->model->actualizarEstado($id, $estado);

            header('Content-Type: application/json');

            echo json_encode([
                'exito' => $exito
            ]);

            exit;
        }
    }

    // ERROR
    private function manejarError($mensaje) {

        header(
            "Location: index.php?accion=programa_educativo&msg=error&info=" .
            urlencode($mensaje)
        );

        exit();
    }
}
?>