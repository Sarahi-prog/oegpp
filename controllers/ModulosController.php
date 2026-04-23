<?php
    require_once 'models/ModulosModel.php';
    require_once 'models/Modulos.php';
    require_once 'helpers/loggers.php';

    class ModulosController{

        public function cargar(){
            try {
                $model = new ModulosModel();
                $modulos = $model->cargar();
                require './views/viewCargarModulos.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new ModulosModel();
                $modulos = $model->cargarsinR();
                require './views/viewCargarModulosSinR.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function modificar(){
            try {
                if(isset($_POST['id_modulo']) && isset($_POST['codigo_modulo']) && isset($_POST['nombre_modulo']) && isset($_POST['descripcion'])){
                    $modulos = new Modulos();
                    $modulos->setid_modulo($_POST['id_modulo']);
                    $modulos->setCodigoModulo($_POST['codigo_modulo']);
                    $modulos->setNombreModulo($_POST['nombre_modulo']);
                    $modulos->setDescripcion($_POST['descripcion']);
                    $model = new ModulosModel();
                    $model->modificar($modulos);
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                if(isset($_POST['codigo_modulo']) && isset($_POST['nombre_modulo']) && isset($_POST['descripcion'])){
                    $modulos = new Modulos();
                    $modulos->setCodigoModulo($_POST['codigo_modulo']);
                    $modulos->setNombreModulo($_POST['nombre_modulo']);
                    $modulos->setDescripcion($_POST['descripcion']);
                    $model = new ModulosModel();
                    $model->guardar($modulos);
                } else {
                    require './views/viewGuardarModulo.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>