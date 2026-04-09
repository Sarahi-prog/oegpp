<?php
    require_once 'models/AdministradoresModel.php';
    require_once 'models/Administradores.php';
    require_once 'helpers/loggers.php';

    class AdministradoresController{

        public function login() {
            try{
                if (session_status() === PHP_SESSION_NONE) session_start();
                // Si ya hay sesión activa, redirigir
                if (isset($_SESSION['usuario'])) {
                    header('Location: index.php?accion=paginainicio');
                    exit;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $usuario = $_POST['usuario'] ?? '';
                    $password = $_POST['password'] ?? '';
                    $ip = $_SERVER['REMOTE_ADDR'];

                    $adminModel = new AdministradoresModel();
                    $intentosModel = new IntentosLoginModel();
                    $sesionesModel = new SesionesModel();

                    // Verificar si el usuario está bloqueado
                    $estado = $adminModel->estaBloqueado($usuario);
                    if ($estado && $estado['bloqueado']) {
                        $error = "La cuenta está bloqueada. Contacte al administrador.";
                        require './views/viewLogin.php';
                        return;
                    }

                    // Validar credenciales
                    $resultado = $adminModel->validarLogin($usuario, $password);

                    if ($resultado !== null) {
                        // Registrar intento exitoso
                        $log = new IntentosLogin($usuario, $ip, true);
                        $intentosModel->guardar($log);

                        // Crear sesión en tabla de sesiones
                        $sesionesModel->crearSesion($resultado->getIdUsuario(), session_id(), $ip);

                        // Guardar en $_SESSION
                        $_SESSION['usuario'] = $resultado->getUsuario();
                        $_SESSION['idusuario'] = $resultado->getIdUsuario();

                        header('Location: index.php?accion=paginainicio');
                        exit;
                    } else {
                        // Registrar intento fallido
                        $log = new IntentosLogin($usuario, $ip, false);
                        $intentosModel->guardar($log);

                        // Contar intentos fallidos en últimas 24h
                        $fallidos = $intentosModel->contarFallidos24h($usuario);

                        if ($fallidos >= 10) {
                            $adminModel->bloquearUsuario($usuario);
                            $error = "La cuenta ha sido bloqueada por exceso de intentos fallidos.";
                        } else {
                            $error = "Credenciales incorrectas. Intentos fallidos: $fallidos";
                        }

                        require './views/viewLogin.php';
                    }
                } else {
                    require './views/viewLogin.php';
                }
            }catch(Exception $e){
                Logger::error($e);
            }
        }

        public function logout() {
            try{
                if (session_status() === PHP_SESSION_NONE) session_start();

                $sesionesModel = new SesionesModel();
                if (isset($_SESSION['idusuario'])) {
                    $sesionesModel->cerrarSesion($_SESSION['idusuario'], session_id());
                }

                session_unset();
                session_destroy();
                header('Location: index.php?accion=login');
                exit;
            } catch(Exception $e){
                Logger::error($e);
            }
        }

        public function paginainicio() {
            require './helpers/verificacion.php';
            require './views/viewPaginainicio.php';
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