<?php
require_once 'models/RegistrosCapacitacion.php';
require_once 'models/RegistrosCapacitacionModel.php';
require_once 'helpers/loggers.php';

class RegistroCapacitacionController{
    public function cargar(){
        try {
            $model = new RegistroCapacitacionModel();
            echo "SIN MODELO";
            $registroscapacitacion = $model->cargar();
            echo "CON MODELO";
            require './views/registros_capacitacion.php';
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
                header("Location: index.php?accion=registros_capacitacion");
                exit();
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