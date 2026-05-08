<?php
require_once 'models/ModulosModel.php';
require_once 'models/Modulos.php';
require_once 'helpers/loggers.php';

class ModulosController {

    public function cargar() {
        try {
            $model = new ModulosModel();
            $modulos = $model->cargar();
            require './views/modulos.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function listarModulos() {
        // Página actual desde GET
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $registrosPorPagina = 20;

        // Llamar al modelo para traer los clientes de esa página
        $modulos = $this->model->obtenerModulosPaginados($pagina, $registrosPorPagina);

        // Calcular total de páginas
        $totalRegistros = $this->model->contarModulos();
        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

        // Pasar datos a la vista
        $pagina_actual = 'modulos';
        require './views/modulos.php';
    }

    public function buscar() {
        try {
            $texto = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
            $campo = isset($_POST['campo']) ? $_POST['campo'] : null;
            $model = new ModulosModel();
            $modulos = $model->buscar($texto, $campo);
            require './views/modulos.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar() {
        try {
            // Validamos los campos reales que tiene tu clase Modulos
            if(isset($_POST['id_modulo']) && isset($_POST['curso_id']) && isset($_POST['nombre_modulo'])){
                $modulo = new Modulos();
                
                // Usamos los nombres exactos de tus setters
                $modulo->setIdModulo($_POST['id_modulo']);
                $modulo->setCursoId($_POST['curso_id']);
                $modulo->setNombreModulo($_POST['nombre_modulo']);
                $modulo->setHoras($_POST['horas']);
                $modulo->setFechaInicio($_POST['fecha_inicio']);
                $modulo->setFechaFin($_POST['fecha_fin']);

                $model = new ModulosModel();
                $model->modificar($modulo);
                
                header("Location: index.php?accion=modulos");
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function guardar() {
        try {
            if(isset($_POST['curso_id']) && isset($_POST['nombre_modulo'])){
                $modulo = new Modulos();
                $modulo->setCursoId($_POST['curso_id']);
                $modulo->setNombreModulo($_POST['nombre_modulo']);
                $modulo->setHoras($_POST['horas']);
                $modulo->setFechaInicio($_POST['fecha_inicio']);
                $modulo->setFechaFin($_POST['fecha_fin']);

                $model = new ModulosModel();
                $model->guardar($modulo);
                
                header("Location: index.php?accion=modulos");
            } else {
                require './views/modulo.php';
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
}