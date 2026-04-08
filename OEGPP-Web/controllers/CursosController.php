<?php
    require_once 'models/CursosModel.php';
    require_once 'models/Cursos.php';
    require_once 'helpers/loggers.php';

    class CursosController{

        public function cargar(){
            try {
                $model = new CursosModel();
                $cursos = $model->cargar();
                require './views/viewCargarCursos.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new CursosModel();
                $cursos = $model->cargarsinR();
                require './views/viewCargarCursosSinR.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function modificar(){
            try {
                if(isset($_POST['id_curso']) && isset($_POST['codigo_curso']) && isset($_POST['nombre_curso']) && isset($_POST['horas_totales'])){
                    $cursos = new Cursos();
                    $cursos->setid_curso($_POST['id_curso']);
                    $cursos->setCodigoCurso($_POST['codigo_curso']);
                    $cursos->setNombreCurso($_POST['nombre_curso']);
                    $cursos->setHorasTotales($_POST['horas_totales']);
                    $model = new CursosModel();
                    $model->modificar($cursos);
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                if(isset($_POST['codigo_curso']) && isset($_POST['nombre_curso']) && isset($_POST['horas_totales'])){
                    $cursos = new Cursos();
                    $cursos->setCodigoCurso($_POST['codigo_curso']);
                    $cursos->setNombreCurso($_POST['nombre_curso']);
                    $cursos->setHorasTotales($_POST['horas_totales']);
                    $model = new CursosModel();
                    $model->guardar($cursos);
                } else {
                    require './views/viewGuardarCurso.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>