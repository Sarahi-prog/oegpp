<?php
    require_once 'models/TrabajadoresModel.php';
    require_once 'models/Trabajadores.php';
    require_once 'helpers/loggers.php';

    class TrabajadoresController{

        public function cargar(){
            try {
                $model = new TrabajadoresModel();
                $trabajadores = $model->cargar();
                require './views/viewCargarTrabajadores.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new TrabajadoresModel();
                $trabajadores = $model->cargarsinR();
                require './views/viewCargarTrabajadoresSinR.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function modificar(){
            try {
                if(isset($_POST['id_trabajador']) && isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['cedula']) && isset($_POST['email'])){
                    $trabajadores = new Trabajadores();
                    $trabajadores->setid_trabajador($_POST['id_trabajador']);
                    $trabajadores->setNombre($_POST['nombre']);
                    $trabajadores->setApellido($_POST['apellido']);
                    $trabajadores->setCedula($_POST['cedula']);
                    $trabajadores->setEmail($_POST['email']);
                    $model = new TrabajadoresModel();
                    $model->modificar($trabajadores);
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                if(isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['cedula']) && isset($_POST['email'])){
                    $trabajadores = new Trabajadores();
                    $trabajadores->setNombre($_POST['nombre']);
                    $trabajadores->setApellido($_POST['apellido']);
                    $trabajadores->setCedula($_POST['cedula']);
                    $trabajadores->setEmail($_POST['email']);
                    $model = new TrabajadoresModel();
                    $model->guardar($trabajadores);
                } else {
                    require './views/viewGuardarTrabajador.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>