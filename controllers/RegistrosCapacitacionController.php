<?php
require_once 'models/RegistrosCapacitacion.php';
require_once 'models/RegistrosCapacitacionModel.php';
require_once 'models/CursosModel.php';
require_once 'models/TrabajadoresModel.php';
require_once 'models/LibrosRegistroModel.php';
require_once 'helpers/loggers.php';

class RegistroCapacitacionController{
    public function cargar(){
        try {
            $model = new RegistroCapacitacionModel();
            $registroscapacitacion = $model->cargar();

            $modelCurso = new CursosModel();
            $cursos = $modelCurso->cargar();

            $modelTrabajador = new TrabajadoresModel();
            $trabajadores = $modelTrabajador->cargar();

            $modelLibro = new LibrosRegistroModel();
            $libros = $modelLibro->cargar();
            require './views/registros_capacitacion.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function guardar() {
        try {
            echo "Entró al método guardar_capacitacion<br>";
            var_dump($_POST); 
            $registrocapacitacion = new RegistroCapacitacion();
            $registrocapacitacion->setTrabajadorId($_POST['trabajador_id'] ?? null);
            $registrocapacitacion->setCursoId($_POST['curso_id'] ?? null);
            $registrocapacitacion->setLibroId($_POST['libro_id'] ?? null);
            $registrocapacitacion->setRegistro($_POST['registro'] ?? null);
            $registrocapacitacion->setHorasRealizadas($_POST['horas_realizadas'] ?? null);
            $registrocapacitacion->setFechaInicio($_POST['fecha_inicio'] ?? null);
            $registrocapacitacion->setFechaFin($_POST['fecha_fin'] ?? null);
            $registrocapacitacion->setFechaEmision($_POST['fecha_emision'] ?? null);
            $registrocapacitacion->setFolio($_POST['folio'] ?? null);
            $registrocapacitacion->setEstado($_POST['estado'] ?? 'Activo');

            $model = new RegistroCapacitacionModel();
            $model->guardar($registrocapacitacion);

            header("Location: index.php?accion=registros_capacitacion");
            exit();
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar(){
        try {
            if(isset($_POST['id_registro']) && isset($_POST['id_trabajador']) && isset($_POST['id_curso']) && isset($_POST['fecha_emision']) && isset($_POST['folio']) && isset($_POST['fecha_registro']) && isset($_POST['estado'])){
                $registrocapacitacion = new RegistroCapacitacion();
                $registrocapacitacion->setIdRegistro($_POST['id_registro']);
                $registrocapacitacion->setIdTrabajador($_POST['id_trabajador']);
                $registrocapacitacion->setIdCurso($_POST['id_curso']);
                $registrocapacitacion->setFechaEmision($_POST['fecha_emision']);
                $registrocapacitacion->setFolio($_POST['folio']);
                $registrocapacitacion->setFechaRegistro($_POST['fecha_registro']);
                $registrocapacitacion->setEstado($_POST['estado']);
                $model = new RegistroCapacitacionModel();
                $model->modificar($registrocapacitacion);
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
}
?>