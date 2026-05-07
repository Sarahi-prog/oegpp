<?php
require_once './models/RegistroCapacitacion.php';
require_once './models/RegistroCapacitacionModel.php';
require_once 'models/CursosModel.php';
require_once 'models/ClientesModel.php';
require_once 'models/LibrosRegistroModel.php';
require_once 'helpers/loggers.php';

class RegistroCapacitacionController {

    private $model;

    public function __construct() {
        $this->model = new RegistroCapacitacionModel();
    }

    public function listarRegistros() {
        $registros = $this->model->cargar_registro();
        $pagina_actual = 'registros_capacitacion';        
        $modelCurso = new CursosModel();
        $cursos = $modelCurso->cargar();
        $modelCliente = new ClientesModel();
        $clientes = $modelCliente->cargar();
        $modelLibro = new LibrosRegistroModel();
        $libros = $modelLibro->cargar();
        require './views/registros_capacitacion.php';
    }

    public function guardarRegistro() {
        if (isset($_POST['cliente_id'], $_POST['curso_id'])) {

            $registro = $this->mapearDatosFormulario();
            $id_registro = $_POST['id_registro'] ?? '';

            if (!empty($id_registro)) {
                // --- MODO EDICIÓN ---
                $registro->setIdRegistro($id_registro);

                if ($this->model->modificar_registro($registro)) {
                    header("Location: index.php?accion=registros&msg=actualizado");
                    exit();
                } else {
                    $this->manejarError("Error al actualizar registro");
                }

            } else {
                // --- MODO NUEVO ---
                $idGenerado = $this->model->guardar_registro($registro);

                if ($idGenerado !== null) {
                    header("Location: index.php?accion=registros_capacitacion&msg=guardado");
                    exit();
                } else {
                    $this->manejarError("Error al guardar registro");
                }
            }
        }
    }

    private function manejarError($mensaje) {
        header("Location: index.php?accion=registros_capacitacion&msg=error&info=" . urlencode($mensaje));
        exit();
    }

    public function eliminarRegistro() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if ($this->model->eliminar_registro($id)) {
                header("Location: index.php?accion=registros_capacitacion&msg=eliminado");
                exit();
            } else {
                $this->manejarError("Error al eliminar registro");
            }
        }
    }

    private function mapearDatosFormulario() {
        $registro = new RegistroCapacitacion();

        $registro->setClienteId($_POST['cliente_id']);
        $registro->setCursoId($_POST['curso_id']);
        $registro->setLibroId($_POST['libro_id']);
        $registro->setRegistro($_POST['registro']);
        $registro->setHorasRealizadas($_POST['horas_realizadas']);

        $registro->setFechaInicio(!empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null);
        $registro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
        $registro->setFechaEmision(!empty($_POST['fecha_emision']) ? $_POST['fecha_emision'] : null);

        $registro->setFolio($_POST['folio'] ?? null);
        $registro->setEstado($_POST['estado'] ?? 'Activo');

        return $registro;
    }

    public function modificarRegistro() {
        if(isset($_POST['id_registro']) && !empty($_POST['id_registro'])) {
            $registro = $this->mapearDatosFormulario();
            $registro->setIdRegistro($_POST['id_registro']); // Asignamos el ID oculto del form

            if($this->model->modificar_registro($registro)) {
                header("Location: index.php?accion=registros_capacitacion&msg=actualizado");
                exit();
            } else {
                $this->manejarError("Error al actualizar registro");
            }
        } else {
            $this->manejarError("Error: No se proporcionó un ID válido para modificar.");
        }
    }


    public function buscarPorDni() {
        header('Content-Type: application/json');

        $dni = $_GET['dni'] ?? '';

        if (empty($dni)) {
            echo json_encode(['error' => 'DNI no proporcionado']);
            exit();
        }

        $registros = $this->model->buscarPorDni($dni);

        if ($registros) {
            echo json_encode(['encontrado' => true, 'registros' => $registros]);
        } else {
            echo json_encode(['encontrado' => false]);
        }

        exit();
    }
}
?>