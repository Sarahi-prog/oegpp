<?php
    require_once 'models/TrabajadoresModel.php';
    require_once 'models/Trabajadores.php';
    require_once 'helpers/loggers.php';

    class TrabajadoresController{
        public function inicioDashboard() {
            $pagina_actual = 'inicio';
            require './views/dashboard.php';
        }
        
        public function cargar(){
            try {
                $model = new TrabajadoresModel();
                $trabajadores = $model->cargar();
                require './views/clientes.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new TrabajadoresModel();
                $trabajadores = $model->cargarsinR();
                require './views/clientes.php';
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
                if(isset($_POST['nombres']) && isset($_POST['apellidos'])){
                    $trabajadores = new Trabajadores();
                    $trabajadores->setDni($_POST['dni']);
                    $trabajadores->setNombres($_POST['nombres']);
                    $trabajadores->setApellidos($_POST['apellidos']);
                    $trabajadores->setCorreo($_POST['correo']);
                    $trabajadores->setCelular($_POST['celular']);
                    $trabajadores->setArea($_POST['area']);
                    $trabajadores->setEstado($_POST['estado']);
                    $model = new TrabajadoresModel();
                    $model->guardar($trabajadores);
                } else {
                    require './views/clientes.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>