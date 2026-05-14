<?php
require_once './models/ProgramaEducativoModel.php';
require_once './models/ProgramaEducativo.php';

class ProgramaEducativoController {
    private $model;

    public function __construct() {
        $this->model = new ProgramaEducativoModel();
    }

    // Carga todos los cursos
    public function cargar() {
        $programas = $this->model->cargarProgramaEducativo();
        require './views/programa_educativo.php';
    }

    // LLAMADO A DIPLOMADOS (cargarD)
    public function cargarD() {
        $programas = $this->model->cargarD();
        require './views/programa_educativo.php'; // Usa la misma vista o una específica
    }

    // LLAMADO A CERTIFICADOS (cargarC)
    public function cargarC() {
        $programas = $this->model->cargarC();
        require './views/programa_educativo.php';
    }

    public function modificarProgramaEducativo() {
        if (isset($_POST['id_programa'])) {
            $programa = new ProgramaEducativo();
            $programa->setId((int)$_POST['id']);
            $programa->setCodigo($_POST['codigo'] ?? '');
            $programa->setNombre($_POST['nombre'] ?? '');
            $programa->setTipo($_POST['tipo'] ?? '');
            $programa->setHorasTotales((int)($_POST['horas_totales'] ?? 0));
            
            // CORRECCIÓN AQUÍ: Si no viene el estado, enviamos 1 por defecto
            $programa->setEstado((int)($_POST['estado'] ?? 1)); 

            if ($this->model->modificarProgramaEducativo($programa)) {
                header("Location: index.php?accion=programa_educativo&msg=actualizado");
                exit();
            } else {
                $this->manejarError("Error al actualizar el programa educativo.");
            }
        }
    }

    public function guardarProgramaEducativo() {
        if (isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['horas_totales'])) {
            $programa = new ProgramaEducativo();
            $programa->setCodigo($_POST['codigo']);
            $programa->setNombre($_POST['nombre']);
            $programa->setTipo($_POST['tipo']);
            $programa->setHorasTotales((int)($_POST['horas_totales'] ?? 0));
            $programa->setEstado((int)($_POST['estado'] ?? 1));
            
            if ($this->model->guardarProgramaEducativo($programa)) {
                header("Location: index.php?accion=programa_educativo&msg=guardado");
                exit();
            } else {
                $this->manejarError("Error al guardar el programa educativo.");
            }
        } else {
            $this->manejarError("Faltan datos requeridos para guardar el programa educativo.");
        }
    }

        public function eliminarProgramaEducativo() {
        if (isset($_GET['id'])) {
            $id_curso = $_GET['id'];

            if ($this->model->eliminarProgramaEducativo($id_programa)) {
                header("Location: index.php?accion=programa_educativo&res=eliminado");
                exit();
            } else {
                echo "Error: No se pudo eliminar. Es posible que el programa educativo esté referenciado en otra tabla.";
            }
        } else {
            echo "Error: No se recibió un ID válido para eliminar.";
        }
    }


    public function actualizar_estado() {
        if (isset($_POST['id']) && isset($_POST['estado'])) {
            $id = intval($_POST['id']);
            $estado = intval($_POST['estado']); // Recibe 1 o 0

            $exito = $this->model->actualizarEstado($id, $estado);

            header('Content-Type: application/json');
            echo json_encode(['exito' => $exito]);
            exit;
        }
    }

    private function manejarError($mensaje) {
    header("Location: index.php?accion=programa_educativo&msg=error&info=" . urlencode($mensaje));
    exit();
}

}
?>