<?php
require_once __DIR__ . '/../models/AdministradoresModel.php';

class LoginController {

    public function mostrarLogin() {
        require_once './views/inicio/login.php';
    }

    public function procesarLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        // 1. ACCESO DE EMERGENCIA (Bypass)
        if ($usuario === 'admin' && $password === '123456') {
            $_SESSION['admin_id'] = 1; 
            $_SESSION['admin_usuario'] = 'admin';
            $_SESSION['rol'] = 'admin_general'; 
            $_SESSION['admin_general'] = true;
            header("Location: index.php?accion=inicio");
            exit();
        }

        // 2. VALIDACIÓN CON BASE DE DATOS
        $model = new AdministradoresModel();
        $admin = $model->verificarCredenciales($usuario, $password);

        if ($admin) {
            // VERIFICAR SI ESTÁ APROBADO (verificado)
            if (isset($admin['verificado']) && ($admin['verificado'] == true || $admin['verificado'] == 1)) {
                
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_usuario'] = $admin['usuario'];
                $_SESSION['rol'] = $admin['rol']; 
                
                // Permiso general para admins
                $_SESSION['admin_general'] = ($admin['rol'] === 'administrador' || $admin['rol'] === 'admin_general');

                header("Location: index.php?accion=inicio");
                exit();
            } else {
                // Usuario existe pero no está aprobado
                $error = "Tu cuenta aún no ha sido autorizada por el Administrador.";
                require_once './views/inicio/login.php';
            }
        } else {
            // Credenciales incorrectas
            $error = "Usuario o contraseña incorrectos.";
            require_once './views/inicio/login.php';
        }
    } // Cierre de procesarLogin

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php?accion=login");
        exit();
    }
} // Cierre de la CLASE (Esta es la que probablemente faltaba)