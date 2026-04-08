<?php
    require_once 'models/AdministradoresModel.php';
    require_once 'models/Administradores.php';
    require_once 'helpers/loggers.php';

    class AdministradoresController{
        public function login() {
            try {
                if (isset($_POST['usuario']) && isset($_POST['password'])) {
                    $usuario = $_POST['usuario'];
                    $password = $_POST['password'];
                    $ip = $_SERVER['REMOTE_ADDR'];

                    $adminModel = new AdministradoresModel();
                    $intentosModel = new IntentosLoginModel();

                    // 1. Verificar si la cuenta está bloqueada
                    $estado = $adminModel->estaBloqueado($usuario);
                    if ($estado && $estado['bloqueado']) {
                        echo "La cuenta está bloqueada. Contacte al administrador para desbloquear.";
                        return;
                    }

                    // 2. Validar credenciales
                    $admin = new Administradores();
                    $admin->setUsuario($usuario);
                    $admin->setPassword($password);

                    $resultado = $adminModel->validarLogin($admin);

                    if ($resultado) {
                        // Login correcto → registrar intento exitoso
                        $log = new IntentosLogin();
                        $log->setIp($ip);
                        $log->setUsuario($usuario);
                        $log->setExitoso(true);
                        $intentosModel->guardar($log);

                        echo "Bienvenido, " . $resultado->getUsuario();
                    } else {
                        // Login incorrecto → registrar intento fallido
                        $log = new IntentosLogin();
                        $log->setIp($ip);
                        $log->setUsuario($usuario);
                        $log->setExitoso(false);
                        $intentosModel->guardar($log);

                        // 3. Contar intentos fallidos en 24h por usuario
                        $fallidos = $intentosModel->verificarPorUsuario($usuario);

                        if ($fallidos['total'] >= 30) {
                            $adminModel->bloquearUsuario($usuario);
                            echo "La cuenta ha sido bloqueada por exceso de intentos fallidos.";
                        } else {
                            echo "Credenciales incorrectas. Intentos fallidos: " . $fallidos['total'];
                        }
                    }
                } else {
                    require './views/viewLogin.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
                require './views/viewError.php';
            }
        }

        public function cargar(){
            try {
                $model = new AdministradoresModel();
                $administradores = $model->cargar();
                require './views/viewCargarAdministradores.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new AdministradoresModel();
                $administradores = $model->cargarsinR();
                require './views/viewCargarAdministradoresSinR.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function modificar(){
            try {
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
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                if(isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['correo']) && isset($_POST['verificado']) && isset($_POST['rol'])){
                    $administradores = new Administradores();
                    $administradores->setUsuario($_POST['usuario']);
                    $administradores->setPassword($_POST['password']);
                    $administradores->setCorreo($_POST['correo']);
                    $administradores->setVerificado($_POST['verificado']);
                    $administradores->setRol($_POST['rol']);
                    $model = new AdministradoresModel();
                    $model->guardar($administradores);
                } else {
                    require './views/viewGuardarAdministrador.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>