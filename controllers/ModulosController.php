<?php
    require_once 'models/ModulosModel.php';
    require_once 'models/CursosModel.php';
    require_once 'helpers/loggers.php';

    class ModulosController {

        public function listar() {
            try {
                $model = new ModulosModel();
                $modulos = $model->cargar();

                $cursosModel = new CursosModel();
                $cursos = $cursosModel->cargar();

                require './views/modulos.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar() {
            try {
                if (isset($_POST['curso_id']) && isset($_POST['nombre_modulo']) && isset($_POST['horas'])) {
                    $modulo = new Modulos();
                    $modulo->setCursoId($_POST['curso_id']);
                    $modulo->setNombreModulo($_POST['nombre_modulo']);
                    $modulo->setHoras($_POST['horas']);
                    $modulo->setFechaInicio($_POST['fecha_inicio'] ?? null);
                    $modulo->setFechaFin($_POST['fecha_fin'] ?? null);

                    $model = new ModulosModel();
                    $model->guardar($modulo);

                    header('Location: index.php?accion=modulos');
                    exit;
                } else {
                    $this->listar();
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>