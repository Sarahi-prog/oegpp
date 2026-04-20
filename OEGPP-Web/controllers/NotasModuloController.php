<?php
require_once 'models/NotasModuloModel.php';
require_once 'models/NotasModulo.php';
require_once 'helpers/loggers.php';
class NotasModuloController {
    public function cargar(){
        try {
            $model = new NotasModuloModel();
            $notasmodulo = $model->cargar();
            require './views/viewCargarNotasModulo.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
    public function guardar(){
        try {
            if(isset($_POST['id_modulo']) && isset($_POST['id_trabajador']) && isset($_POST['nota'])){
                $notasmodulo = new NotasModulo();
                $notasmodulo->setIdModulo($_POST['id_modulo']);
                $notasmodulo->setIdTrabajador($_POST['id_trabajador']);
                $notasmodulo->setNota($_POST['nota']);
                $model = new NotasModuloModel();
                $model->guardar($notasmodulo);
            } else {
                require './views/viewGuardarNotasModulo.php';
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar(){
        try {
            if(isset($_POST['id_nota_modulo']) && isset($_POST['id_modulo']) && isset($_POST['id_trabajador']) && isset($_POST['nota'])){
                $notasmodulo = new NotasModulo();
                $notasmodulo->setIdNotaModulo($_POST['id_nota_modulo']);
                $notasmodulo->setIdModulo($_POST['id_modulo']);
                $notasmodulo->setIdTrabajador($_POST['id_trabajador']);
                $notasmodulo->setNota($_POST['nota']);
                $model = new NotasModuloModel();
                $model->modificar($notasmodulo);
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
}
?>