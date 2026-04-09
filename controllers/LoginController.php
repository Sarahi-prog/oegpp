<?php

class LoginController {

    public function mostrarLogin() {
        require_once './views/inicio/login.php';
    }

    public function procesarLogin() {
        // Ejemplo básico (ajusta con tu BD)
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($usuario === 'admin' && $password === '1234') {
            $_SESSION['admin_id'] = 1;

            header("Location: index.php?accion=inicio");
            exit();
        } else {
            echo "Usuario o contraseña incorrectos";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?accion=login");
        exit();
    }
}