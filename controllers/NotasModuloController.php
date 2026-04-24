<?php
require_once 'models/NotasModuloModel.php';
require_once 'models/NotasModulo.php';
require_once 'models/CursosModel.php';
require_once 'models/TrabajadoresModel.php';
require_once 'models/ModulosModel.php';
require_once 'helpers/loggers.php';

class NotasModuloController {

        public function cargar() {
            try {
                $modelNotas = new NotasModuloModel();
                $modelCursos = new CursosModel();
                $modelClientes = new ClientesModel(); // antes TrabajadoresModel
                $modelModulos = new ModulosModel();

                // Cargar todos los cursos
                $cursos = $modelCursos->cargar();

                // Capturar curso seleccionado
                $cursoSeleccionado = isset($_GET['curso_id']) ? $_GET['curso_id'] : null;

                // Inicializar variables
                $modulos = [];
                $clientes = [];
                $pivotes = [];

                if (!empty($cursoSeleccionado)) {
                    // Filtrar módulos y notas por curso usando el pivote
                    $modulos = $modelModulos->cargarPorCurso($cursoSeleccionado);
                    $clientes = $modelClientes->cargar();
                    $pivotes = $modelNotas->cargarPivotPorCurso($cursoSeleccionado);
                }

                require './views/notas.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }


    public function guardar() {
        try {
            if (isset($_POST['id_modulo']) && isset($_POST['id_trabajador']) && isset($_POST['nota'])) {
                $notasmodulo = new NotasModulo();
                $notasmodulo->setModuloId($_POST['id_modulo']);
                $notasmodulo->setTrabajadorId($_POST['id_trabajador']);
                $notasmodulo->setNota($_POST['nota']);
                $notasmodulo->setFechaRegistro(date('Y-m-d'));

                $model = new NotasModuloModel();
                $model->guardar($notasmodulo);
            }
            // Después de guardar, recargamos la vista
            $this->cargar();

        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar() {
        try {
            if (isset($_POST['id_nota']) && isset($_POST['id_modulo']) && isset($_POST['id_trabajador']) && isset($_POST['nota'])) {
                $notasmodulo = new NotasModulo();
                $notasmodulo->setIdNota($_POST['id_nota']);
                $notasmodulo->setModuloId($_POST['id_modulo']);
                $notasmodulo->setTrabajadorId($_POST['id_trabajador']);
                $notasmodulo->setNota($_POST['nota']);

                $model = new NotasModuloModel();
                $model->modificar($notasmodulo);
            }
            // Después de modificar, recargamos la vista
            $this->cargar();

        } catch (Exception $e) {
            Logger::error($e);
        }
    }
}
?>
