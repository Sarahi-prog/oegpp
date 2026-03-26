<?php
    require_once 'models/AdministradoresModel.php';
    require_once 'models/Administradores.php';

    class AdministradoresController{

        public function cargar(){
            $model = new AdministradoresModel();
            $administradores = $model->cargar();
            require './views/viewCargarAdministradores.php';
        }

        public function cargarsinR(){
            $model = new AdministradoresModel();
            $administradores = $model->cargarsinR();
            require './views/viewCargarAdministradoresSinR.php';
        }

        public function modificar(){
            if(isset($_POST['id_administrador']) && isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['correo']) && isset($_POST['verificado']) && isset($_POST['rol'])){
                $administradores = new Administradores();
                $administradores->setid_admin($_POST['id_administrador']);
                $administradores->setUsuario($_POST['usuario']);
                $administradores->setPassword($_POST['password']);
                $administradores->setCorreo($_POST['correo']);
                $administradores->setVerificado($_POST['verificado']);
                $administradores->setRol($_POST['rol']);
                $model = new AdministradoresModel();
                $model->modificar($administradores);
            }
        }

        public function guardar(){
            
        }
    }
?>