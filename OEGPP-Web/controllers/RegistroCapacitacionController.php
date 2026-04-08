<?php
require_once 'models/RegistroCapacitacion.php';
require_once 'models/RegistroCapacitacionModel.php';
require_once 'helpers/loggers.php';

class RegistroCapacitacionController{
    public function cargar(){
        try {
            $model = new RegistroCapacitacionModel();
            $registroscapacitacion = $model->cargar();
            require './views/viewCargarRegistrosCapacitacion.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
    public function guardar(){
        try {
            if(isset($_POST['id_trabajador']) && isset($_POST['id_curso']) && isset($_POST['fecha_emision']) && isset($_POST['folio']) && isset($_POST['fecha_registro']) && isset($_POST['estado'])){
                $registrocapacitacion = new RegistroCapacitacion();
                $registrocapacitacion->setIdTrabajador($_POST['id_trabajador']);
                $registrocapacitacion->setIdCurso($_POST['id_curso']);
                $registrocapacitacion->setFechaEmision($_POST['fecha_emision']);
                $registrocapacitacion->setFolio($_POST['folio']);
                $registrocapacitacion->setFechaRegistro($_POST['fecha_registro']);
                $registrocapacitacion->setEstado($_POST['estado']);
                $model = new RegistroCapacitacionModel();
                $model->guardar($registrocapacitacion);
            } else {
                require './views/viewGuardarRegistroCapacitacion.php';
            }
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