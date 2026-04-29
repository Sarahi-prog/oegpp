<?php
require_once 'models/ModulosModel.php';
require_once 'models/Modulos.php';
require_once 'models/CursosModel.php';
require_once 'helpers/loggers.php';

class ModulosController {

    public function cargar() {
        try {
            $model = new ModulosModel();
            $id_curso = $_GET['id'] ?? null;

            if ($id_curso) {
                $modulos = $model->buscarPorCurso($id_curso);
            } else {
                $modulos = $model->cargar();
            }

            $cursosModel = new CursosModel();
            $cursos_disponibles = $cursosModel->cargarCurso();

            require './views/modulos.php';
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
                $modulo->setFechaInicio(!empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null);
                $modulo->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);

                $model = new ModulosModel();
                $model->guardar($modulo);

                header("Location: index.php?accion=modulos");
                exit();
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar() {
        try {
            if(isset($_POST['id_modulo']) && isset($_POST['curso_id']) && isset($_POST['nombre_modulo'])){
                $modulo = new Modulos();
                $modulo->setIdModulo($_POST['id_modulo']);
                $modulo->setCursoId($_POST['curso_id']);
                $modulo->setNombreModulo($_POST['nombre_modulo']);
                $modulo->setHoras($_POST['horas']);
                $modulo->setFechaInicio(!empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null);
                $modulo->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);

                $model = new ModulosModel();
                $model->modificar($modulo);

                header("Location: index.php?accion=modulos");
                exit();
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function cambiarEstado() {
        try {
            $id = $_GET['id'] ?? null;
            $estado = $_GET['estado'] ?? null;

            if($id !== null && $estado !== null) {
                $model = new ModulosModel();
                $model->cambiarEstado((int)$id, (int)$estado);
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
        header("Location: index.php?accion=modulos");
        exit();
    }

    public function eliminar() {
        try {
            $id = $_GET['id'] ?? null;
            if($id) {
                $model = new ModulosModel();
                $model->eliminar((int)$id);
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
        header("Location: index.php?accion=modulos");
        exit();
    }
}