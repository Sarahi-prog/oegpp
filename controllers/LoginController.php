<?php
require_once __DIR__ . '/../models/AdministradoresModel.php';
// IMPORTANTE: Requerir los archivos de intentos
require_once __DIR__ . '/../models/IntentosLoginModel.php';
require_once __DIR__ . '/../models/IntentosLogin.php'; 

class LoginController {

    public function mostrarLogin() {
        require_once './views/inicio/login.php';
    }

   public function procesarLogin() {
    ob_start();
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    ob_clean();
    header('Content-Type: application/json');

    $intentosModel = new IntentosLoginModel();

    // 1. VERIFICAR BLOQUEO
    $verificacion = $intentosModel->verificarPorUsuario($usuario);
    if ($verificacion['total'] >= 3) {
        echo json_encode([
            'success' => false, 
            'message' => 'Acceso bloqueado por seguridad (3 intentos fallidos). Contacta al Administrador.'
        ]);
        exit();
    }

    // 2. VALIDACIÓN CON BD (sin bypass)
    $model = new AdministradoresModel();
    $admin = $model->verificarCredenciales($usuario, $password);

    if ($admin) {
        if (isset($admin['verificado']) && ($admin['verificado'] == true || $admin['verificado'] == 1)) {
            $this->registrarIntentoLog($usuario, $ip, true);

            ///token
            $tokenSesion = bin2hex(random_bytes(32));
                $model->guardarTokenSesion($admin['id_admin'], $tokenSesion);


            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_usuario'] = $admin['usuario'];
            $_SESSION['rol'] = $admin['rol']; 
            $_SESSION['token_sesion'] = $tokenSesion;
            $_SESSION['admin_general'] = ($admin['rol'] === 'administrador' || $admin['rol'] === 'admin_general' || $admin['rol'] === 'super_admin');
            echo json_encode([
                'success' => true, 
                'message' => 'Bienvenido ' . $admin['usuario'],
                'redirect' => 'index.php?accion=inicio'
            ]);
            exit();
        } else {
            $this->registrarIntentoLog($usuario, $ip, false);
            echo json_encode([
                'success' => false, 
                'message' => 'Tu cuenta aún no ha sido autorizada.'
            ]);
            exit();
        }
    } else {
        $this->registrarIntentoLog($usuario, $ip, false);
        echo json_encode([
            'success' => false, 
            'message' => 'Usuario o contraseña incorrectos.'
        ]);
        exit();
    }
}

    // FUNCIÓN AUXILIAR para no repetir código de inserción
    private function registrarIntentoLog($usuario, $ip, $exitoso) {
        $intentosModel = new IntentosLoginModel();
        $intento = new IntentosLogin();
        $intento->setUsuario($usuario);
        $intento->setIp($ip);
        $intento->setExitoso($exitoso);
        $intentosModel->guardar($intento);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php?accion=login");
        exit();
    }
}
