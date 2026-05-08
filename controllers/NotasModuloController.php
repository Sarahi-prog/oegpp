<?php
require_once 'models/NotasModuloModel.php';
require_once 'models/NotasModulo.php';
require_once 'models/ClientesModel.php';
require_once 'models/ModulosModel.php';
require_once 'helpers/loggers.php';

class NotasModuloController {

    public function listarNotas() {
        try {
            $model   = new NotasModuloModel();
            $notas   = $model->cargar();

            // ── Cargar clientes y módulos para los selects ──
            $modelClientes = new ClientesModel();
            $clientes      = $modelClientes->cargar();

            $modelModulos  = new ModulosModel();
            $modulos       = $modelModulos->cargar();

            require './views/notas.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function guardarNota() {
        try {
            if (isset($_POST['trabajador_id'], $_POST['modulo_id'], $_POST['nota'])) {
                $notasmodulo = new NotasModulo();
                $notasmodulo->setCliente($_POST['trabajador_id']);
                $notasmodulo->setModuloId($_POST['modulo_id']);
                $notasmodulo->setNota($_POST['nota']);
                $notasmodulo->setFechaRegistro($_POST['fecha_registro'] ?? null);

                $model = new NotasModuloModel();
                $model->guardar($notasmodulo);

                header('Location: index.php?accion=notas');
                exit;
            }
            $this->listarNotas();
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar() {
        try {
            if (isset($_POST['id_nota'], $_POST['trabajador_id'], $_POST['modulo_id'], $_POST['nota'])) {
                $notasmodulo = new NotasModulo();
                $notasmodulo->setIdNota($_POST['id_nota']);
                $notasmodulo->setCliente($_POST['trabajador_id']);
                $notasmodulo->setModuloId($_POST['modulo_id']);
                $notasmodulo->setNota($_POST['nota']);
                $notasmodulo->setFechaRegistro($_POST['fecha_registro'] ?? null); // ← también guarda fecha al editar

                $model = new NotasModuloModel();
                $model->modificar($notasmodulo);

                header('Location: index.php?accion=notas');
                exit;
            }
            $this->listarNotas();
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function listarNotasModulos() {
        // Página actual desde GET
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $registrosPorPagina = 20;

        // Llamar al modelo para traer los clientes de esa página
        $notas = $this->model->obtenerNotasPaginados($pagina, $registrosPorPagina);

        // Calcular total de páginas
        $totalRegistros = $this->model->contarNotas();
        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

        // Pasar datos a la vista
        $pagina_actual = 'notas';
        require './views/notas.php';
    }

    public function eliminar() {
    if (isset($_GET['id'])) {
        $model = new NotasModuloModel();
        $model->eliminar($_GET['id']);
    }
    header('Location: index.php?accion=notas');
    exit;
}
}

