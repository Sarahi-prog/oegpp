<?php
require_once 'models/CursosModel.php';
require_once 'models/Cursos.php';

class CursosController{
    public function cargar(){
        $model = new CursosModel();
        $cursos = $model->cargar();
        require './views/Cursos.php';
    }
    public function cargarD(){
        $model = new CursosModel();
        $cursos = $model->cargarD();
        require './views/viewCargarCursosD.php';
    }
    public function cargarC(){
        $model = new CursosModel();
        $cursos = $model->cargarC();
        require './views/viewCargarCursosC.php';
    }
    public function guardar(){
        if(isset($_POST['codigo_curso']) && isset($_POST['nombre_curso']) && isset($_POST['tipo']) && isset($_POST['horas_totales'])){
            $curso = new Cursos();
            $curso->setCodigoCurso($_POST['codigo_curso']);
            $curso->setNombreCurso($_POST['nombre_curso']);
            $curso->setTipo($_POST['tipo']);
            $curso->setHorasTotales($_POST['horas_totales']);
            $model = new CursosModel();
            $model->guardar($curso);

            header("Location: index.php?controller=cursos&action=cargar");
        } else {
            require './views/viewGuardarCurso.php';
        }
    }
}
?>